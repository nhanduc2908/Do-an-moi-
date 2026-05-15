<?php

namespace App\Http\Controllers\Api\V1\Module07_SourceCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\GitSecurityService;

class GitSecurityController extends Controller
{
    protected $gitService;

    public function __construct(GitSecurityService $gitService)
    {
        $this->gitService = $gitService;
    }

    /**
     * Quét lịch sử commit tìm thông tin nhạy cảm
     */
    public function scanHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
            'branch' => 'nullable|string',
            'depth' => 'nullable|integer|min=1|max=10000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->gitService->scanCommitHistory(
            $request->repository_url,
            $request->branch ?? 'main',
            $request->depth ?? 1000
        );

        return response()->json([
            'success' => true,
            'data' => [
                'scan_id' => $result['scan_id'],
                'total_commits' => $result['total_commits'],
                'sensitive_commits' => $result['sensitive_commits'],
                'findings' => $result['findings'],
            ],
            'message' => 'Quét lịch sử commit hoàn tất'
        ]);
    }

    /**
     * Kiểm tra cấu hình Git
     */
    public function checkConfig(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->gitService->checkSecurityConfig($request->repository_url);

        return response()->json([
            'success' => true,
            'data' => [
                'is_private' => $result['is_private'],
                'has_ci_cd' => $result['has_ci_cd'],
                'has_webhook' => $result['has_webhook'],
                'branch_protection' => $result['branch_protection'],
                'vulnerabilities' => $result['vulnerabilities'],
                'recommendations' => $result['recommendations'],
            ]
        ]);
    }

    /**
     * Kiểm tra webhook an toàn
     */
    public function verifyWebhook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'webhook_url' => 'required|url',
            'secret' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->gitService->verifyWebhookSecurity(
            $request->webhook_url,
            $request->secret
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Phân tích quyền truy cập
     */
    public function analyzeAccess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->gitService->analyzeAccessControl($request->repository_url);

        return response()->json([
            'success' => true,
            'data' => [
                'collaborators' => $result['collaborators'],
                'teams' => $result['teams'],
                'outside_collaborators' => $result['outside_collaborators'],
                'permissions' => $result['permissions'],
                'security_risks' => $result['security_risks'],
            ]
        ]);
    }

    /**
     * Tạo .gitignore an toàn
     */
    public function generateGitignore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_type' => 'required|string|in:laravel,nodejs,python,react,vue,android,ios,django,flask,spring',
            'include_security' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $content = $this->gitService->generateGitignore(
            $request->project_type,
            $request->include_security ?? true
        );

        return response()->json([
            'success' => true,
            'data' => [
                'content' => $content,
                'file_name' => '.gitignore',
            ]
        ]);
    }

    /**
     * Quét tìm commit chứa secret
     */
    public function findSecretsInHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $secrets = $this->gitService->findSecretsInHistory($request->repository_url);

        return response()->json([
            'success' => true,
            'data' => [
                'total_secrets' => count($secrets),
                'secrets' => $secrets,
            ]
        ]);
    }

    /**
     * Tạo báo cáo bảo mật Git
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
            'format' => 'nullable|in:json,pdf,html',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->gitService->generateSecurityReport(
            $request->repository_url,
            $request->format ?? 'json'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Rewrite lịch sử Git (xóa secret)
     */
    public function rewriteHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
            'secret_pattern' => 'required|string',
            'dry_run' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->gitService->rewriteHistory(
            $request->repository_url,
            $request->secret_pattern,
            $request->dry_run ?? true
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => $result['dry_run'] ? 'Dry run completed' : 'History rewritten'
        ]);
    }
}