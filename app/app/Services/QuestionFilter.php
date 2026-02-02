<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Question;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QuestionFilter
{
    protected Builder $query;
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->query = Question::with(['user', 'tags', 'votes', 'category']);
    }

    public function apply(): Builder
    {
        $this->applySearch()
            ->applyTagFilter()
            ->applyStatusFilter()
            ->applyBookmarkedFilter()
            ->applyDateFilter()
            ->applySort()
            ->applyCategoryFilter();

        if (auth()->check()) {
            $this->query->with(['bookmarks' => fn($b) => $b->where('user_id', auth()->id())]);
        }

        return $this->query;
    }

    protected function applyCategoryFilter(): self
    {
        if (!$this->request->filled('category')) {
            return $this;
        }

        $category = Category::with('recursiveChildren')->find($this->request->category);
        if (!$category) {
            return $this;
        }

        $ids = $this->getDescendantIds($category);
        $this->query->whereIn('category_id', $ids);

        return $this;
    }

    protected function getDescendantIds(Category $category): array
    {
        $ids = [$category->id];

        foreach ($category->children as $child) {
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }

        return $ids;
    }

    protected function applySearch(): self
    {
        if ($this->request->filled('search')) {
            $searchTerm = $this->request->search;

            $this->query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%")
                    ->orWhereHas('answers', function ($query) use ($searchTerm) {
                        $query->where('body', 'like', "%{$searchTerm}%");
                    });
            });
        }

        return $this;
    }

    protected function applyTagFilter(): self
    {
        if ($this->request->filled('tag')) {
            $this->query->whereHas('tags', function ($q) {
                $q->where('name', $this->request->tag);
            });
        }

        return $this;
    }

    protected function applyStatusFilter(): self
    {
        if ($this->request->filled('status')) {
            match ($this->request->status) {
                'answered' => $this->query->has('answers'),
                'unanswered' => $this->query->doesntHave('answers'),
                'accepted' => $this->query->whereHas('answers', fn($q) => $q->where('is_accepted', true)),
                default => null
            };
        }

        return $this;
    }

    protected function applyBookmarkedFilter(): self
    {
        if ($this->request->status !== 'bookmarked' || !auth()->check()) {
            return $this;
        }

        $this->query->whereHas('bookmarks', fn($b) => $b->where('user_id', auth()->id()));

        return $this;
    }

    protected function applyDateFilter(): self
    {
        if ($this->request->filled('date')) {
            $this->query->where('created_at', '>=', match ($this->request->date) {
                'today' => today(),
                'week' => now()->subWeek(),
                'month' => now()->subMonth(),
                'year' => now()->subYear(),
                default => null
            });
        }

        return $this;
    }

    protected function applySort(): self
    {
        $sort = $this->request->get('sort', 'latest');

        match ($sort) {
            'votes' => $this->query->withCount('votes')->orderBy('votes_count', 'desc'),
            'oldest' => $this->query->orderBy('created_at', 'asc'),
            default => $this->query->orderBy('created_at', 'desc')
        };

        return $this;
    }
}
