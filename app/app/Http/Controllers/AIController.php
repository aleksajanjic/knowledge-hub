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
        try {
            $answer = $this->autoAnswerService->generateAnswerForQuestion($question, auth()->id());

            if ($answer) {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => __('AI answer generated successfully!'),
                        'answer_id' => $answer->id,
                    ]);
                }
                return redirect()
                    ->route('questions.show', $question)
                    ->with('success', __('AI answer generated successfully!'));
            }

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => __('Failed to generate AI answer.')], 422);
            }
            return redirect()->back()->with('error', __('Failed to generate AI answer. Check audit logs for details.'));
        } catch (Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
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
