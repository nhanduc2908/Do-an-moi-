<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SyncService;
use App\Services\AIService;

class AdminSyncController extends Controller
{
    protected $syncService;
    protected $aiService;

    public function __construct(SyncService $syncService, AIService $aiService)
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
        $this->syncService = $syncService;
        $this->aiService = $aiService;
    }

    /**
     * Dashboard sync
     */
    public function index()
    {
        $lastSync = \App\Models\SyncHistory::latest()->first();
        $pendingSyncs = \App\Models\PendingSync::count();

        return view('admin.sync.index', compact('lastSync', 'pendingSyncs'));
    }

    /**
     * Sync Flutter data
     */
    public function syncFlutter(Request $request)
    {
        $request->validate([
            'direction' => 'required|in:to_flutter,from_flutter,both',
        ]);

        $result = $this->syncService->syncFlutterData($request->direction);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Sync completed successfully.'
        ]);
    }

    /**
     * Sync AI criteria
     */
    public function syncCriteria(Request $request)
    {
        $request->validate([
            'domain_id' => 'nullable|exists:domains,id',
            'count' => 'nullable|integer|min:1|max:100',
        ]);

        $result = $this->aiService->syncCriteria([
            'domain_id' => $request->domain_id,
            'count' => $request->count ?? 50,
        ]);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'AI criteria synced successfully.'
        ]);
    }

    /**
     * Sync vulnerabilities
     */
    public function syncVulnerabilities(Request $request)
    {
        $result = $this->syncService->syncVulnerabilities();

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Vulnerability database synced.'
        ]);
    }

    /**
     * Sync threat intel
     */
    public function syncThreatIntel(Request $request)
    {
        $result = $this->syncService->syncThreatIntelligence();

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Threat intelligence synced.'
        ]);
    }

    /**
     * Sync all
     */
    public function syncAll()
    {
        $results = [
            'flutter' => $this->syncService->syncFlutterData('both'),
            'criteria' => $this->aiService->syncCriteria(['count' => 50]),
            'vulnerabilities' => $this->syncService->syncVulnerabilities(),
            'threat_intel' => $this->syncService->syncThreatIntelligence(),
        ];

        return response()->json([
            'success' => true,
            'data' => $results,
            'message' => 'All syncs completed.'
        ]);
    }

    /**
     * Sync history
     */
    public function history(Request $request)
    {
        $history = \App\Models\SyncHistory::orderBy('created_at', 'desc')
            ->paginate(20);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $history
            ]);
        }

        return view('admin.sync.history', compact('history'));
    }

    /**
     * Retry failed sync
     */
    public function retry($id)
    {
        $sync = \App\Models\SyncHistory::findOrFail($id);
        
        $result = $this->syncService->retrySync($sync);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Retry successful.' : 'Retry failed.'
        ]);
    }

    /**
     * Schedule sync
     */
    public function schedule(Request $request)
    {
        $request->validate([
            'sync_type' => 'required|string',
            'schedule' => 'required|string', // cron expression
        ]);

        $schedule = $this->syncService->scheduleSync($request->all());

        return response()->json([
            'success' => true,
            'data' => $schedule,
            'message' => 'Sync scheduled successfully.'
        ]);
    }

    /**
     * Sync status
     */
    public function status()
    {
        $status = $this->syncService->getSyncStatus();

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }
}