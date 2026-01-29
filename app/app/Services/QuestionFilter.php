<?php

namespace App\Services;

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
        $this->query = Question::with(['user', 'tags', 'votes']);
    }

    public function apply(): Builder
    {
        $this->applySearch()
            ->applyTagFilter()
            ->applyStatusFilter()
            ->applyDateFilter()
            ->applySort();

        return $this->query;
    }

    protected function applySearch(): self
    {
        if ($this->request->filled('search')) {
            $searchTerm = $this->request->search;
            $this->query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('content', 'like', "%{$searchTerm}%");
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
            'votes' => $this->query->orderBy('votes', 'desc'),
            'oldest' => $this->query->orderBy('created_at', 'asc'),
            default => $this->query->orderBy('created_at', 'desc')
        };

        return $this;
    }
}
