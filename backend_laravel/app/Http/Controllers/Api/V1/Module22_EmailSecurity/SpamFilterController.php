<?php

namespace App\Http\Controllers\Api\V1\Module22_EmailSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\SpamRule;
use App\Services\SpamFilterService;

class SpamFilterController extends Controller
{
    protected $spamService;

    public function __construct(SpamFilterService $spamService)
    {
        $this->spamService = $spamService;
    }

    /**
     * Check email
     */
    public function checkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_content' => 'required|string',
            'sender' => 'required|email',
            'subject' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->spamService->analyze($request->all());

        return response()->json([
            'success' => true,
            'data' => [
                'is_spam' => $result['is_spam'],
                'spam_score' => $result['score'],
                'threshold' => $result['threshold'],
                'reasons' => $result['reasons'],
                'action' => $result['action'],
            ]
        ]);
    }

    /**
     * Danh sách rules
     */
    public function rules(Request $request)
    {
        $rules = SpamRule::when($request->type, function($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->is_active, function($query, $isActive) {
                $query->where('is_active', $isActive);
            })
            ->orderBy('priority')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rules
        ]);
    }

    /**
     * Tạo rule
     */
    public function createRule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max=100',
            'condition' => 'required|string',
            'action' => 'required|in:block,allow,quarantine',
            'priority' => 'required|integer|min=1|max=100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $rule = $this->spamService->createRule($request->all());

        return response()->json([
            'success' => true,
            'data' => $rule,
            'message' => 'Tạo rule thành công'
        ]);
    }

    /**
     * Update rule
     */
    public function updateRule(Request $request, $id)
    {
        $rule = SpamRule::findOrFail($id);

        $rule->update($request->only([
            'name', 'condition', 'action', 'priority', 'description', 'is_active'
        ]));

        return response()->json([
            'success' => true,
            'data' => $rule,
            'message' => 'Cập nhật rule thành công'
        ]);
    }

    /**
     * Delete rule
     */
    public function deleteRule($id)
    {
        $rule = SpamRule::findOrFail($id);
        $rule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa rule thành công'
        ]);
    }

    /**
     * Quarantine
     */
    public function quarantine(Request $request)
    {
        $emails = $this->spamService->getQuarantined([
            'start_date' => $request->start_date,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $emails
        ]);
    }

    /**
     * Release email
     */
    public function releaseEmail($id)
    {
        $result = $this->spamService->releaseFromQuarantine($id);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Email đã được release' : 'Release thất bại'
        ]);
    }

    /**
     * Whitelist
     */
    public function whitelist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->spamService->addWhitelist($request->email);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Thêm vào whitelist thành công' : 'Thêm thất bại'
        ]);
    }

    /**
     * Blacklist
     */
    public function blacklist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'reason' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->spamService->addBlacklist($request->email, $request->reason);

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Thêm vào blacklist thành công' : 'Thêm thất bại'
        ]);
    }
}