<?php

namespace App\Http\Controllers\Api\V1\Module05_UrlSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DomainBlacklist;

class DomainBlacklistController extends Controller
{
    /**
     * Danh sách domain bị chặn
     */
    public function index(Request $request)
    {
        $domains = DomainBlacklist::when($request->search, function($query, $search) {
                $query->where('domain', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $domains
        ]);
    }

    /**
     * Thêm domain vào blacklist
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string|unique:domain_blacklists',
            'reason' => 'nullable|string',
            'severity' => 'nullable|in:low,medium,high,critical',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $domain = DomainBlacklist::create([
            'domain' => $request->domain,
            'reason' => $request->reason,
            'severity' => $request->severity ?? 'medium',
            'added_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $domain,
            'message' => 'Thêm domain vào blacklist thành công'
        ]);
    }

    /**
     * Xóa domain khỏi blacklist
     */
    public function destroy($id)
    {
        $domain = DomainBlacklist::findOrFail($id);
        $domain->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa domain khỏi blacklist thành công'
        ]);
    }

    /**
     * Kiểm tra domain
     */
    public function check(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $isBlacklisted = DomainBlacklist::where('domain', $request->domain)
            ->orWhere('domain', 'like', '%' . $request->domain)
            ->exists();

        return response()->json([
            'success' => true,
            'data' => [
                'domain' => $request->domain,
                'is_blacklisted' => $isBlacklisted
            ]
        ]);
    }
}