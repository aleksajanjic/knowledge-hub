<?php

namespace App\Http\Controllers;

use App\Models\AIRequestAudit;
use App\Models\Question;
use App\Services\AutoAnswerService;
use Exception;
use Illuminate\Http\Request;

class AIController extends Controller
{
    private AutoAnswerService $autoAnswerService;

    public function __construct(AutoAnswerService $autoAnswerService)
    {
        $this->autoAnswerService = $autoAnswerService;
    }

    public function auditLogs(Request $request)
    {
        $query = AIRequestAudit::with(['question', 'user'])->orderBy('created_at', 'desc');

        if ($request->has('provider') && $request->provider !== 'all') {
            $query->where('provider', $request->provider);
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $audits = $query->paginate(20);

        $stats = [
            'total_requests' => AIRequestAudit::count(),
            'successful_requests' => AIRequestAudit::where('status', 'success')->count(),
            'failed_requests' => AIRequestAudit::where('status', 'failed')->count(),
            'total_tokens' => AIRequestAudit::sum('tokens_used'),
            'total_cost' => AIRequestAudit::sum('cost'),
            'avg_response_time' => AIRequestAudit::avg('response_time_ms')
        ];

        return view('ai.audit-logs', compact('audits', 'stats'));
    }

    public function generateAnswer(Question $question)
    {
        $this->authorize('update', $question);

        try {
            if ($answer) {
                return redirect()
                    ->route('questions.show', $question)
                    ->with('success', 'AI answer generated successfully!');
            }

            return redirect()
                ->back()
                ->with('error', 'Failed to generate AI answer. Check audit logs for details.');
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        $stats = [
            'total_requests' => AIRequestAudit::count(),
            'successful' => AIRequestAudit::successful()->count(),
            'failed' => AIRequestAudit::failed()->count(),
            'total_tokens' => AIRequestAudit::getTotalTokensUsed(),
            'total_cost' => AIRequestAudit::getTotalCost(),
            'avg_response_time' => round(AIRequestAudit::avg('response_time_ms')),
        ];

        $providerStats = AIRequestAudit::selectRaw('provider, COUNT(*) as count, SUM(tokens_used) as tokens')
            ->groupBy('provider')
            ->get();

        $recentRequests = AIRequestAudit::with(['question', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('ai.dashboard', compact('stats', 'providerStats', 'recentRequests'));
    }
}
