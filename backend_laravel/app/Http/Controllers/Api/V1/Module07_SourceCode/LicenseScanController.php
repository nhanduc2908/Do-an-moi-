<?php

namespace App\Http\Controllers\Api\V1\Module07_SourceCode;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\LicenseScanService;

class LicenseScanController extends Controller
{
    protected $licenseService;

    public function __construct(LicenseScanService $licenseService)
    {
        $this->licenseService = $licenseService;
    }

    /**
     * Quét license trong dự án
     */
    public function scan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'repository_url' => 'required|url',
            'branch' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->licenseService->scanProject(
            $request->repository_url,
            $request->branch ?? 'main'
        );

        return response()->json([
            'success' => true,
            'data' => [
                'scan_id' => $result['scan_id'],
                'total_packages' => $result['total_packages'],
                'license_compliance' => $result['compliance'],
                'conflicts' => $result['conflicts'],
                'details' => $result['details'],
            ]
        ]);
    }

    /**
     * Phân tích license compatibility
     */
    public function checkCompatibility(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'licenses' => 'required|array',
            'licenses.*' => 'string',
            'project_type' => 'nullable|string|in:open_source,commercial,saas',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->licenseService->checkCompatibility(
            $request->licenses,
            $request->project_type ?? 'commercial'
        );

        return response()->json([
            'success' => true,
            'data' => [
                'is_compatible' => $result['compatible'],
                'conflicts' => $result['conflicts'],
                'warnings' => $result['warnings'],
                'recommendations' => $result['recommendations'],
            ]
        ]);
    }

    /**
     * Kiểm tra license của package
     */
    public function checkPackageLicense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'version' => 'required|string',
            'ecosystem' => 'required|in:composer,npm,pypi,go,maven,rubygems',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->licenseService->getPackageLicense(
            $request->name,
            $request->version,
            $request->ecosystem
        );

        return response()->json([
            'success' => true,
            'data' => [
                'package' => $result['package'],
                'license' => $result['license'],
                'spdx_id' => $result['spdx_id'],
                'is_approved' => $result['is_approved'],
                'risk_level' => $result['risk_level'],
                'restrictions' => $result['restrictions'],
            ]
        ]);
    }

    /**
     * Danh sách license được phép
     */
    public function getAllowedLicenses(Request $request)
    {
        $licenses = $this->licenseService->getAllowedLicenses();

        return response()->json([
            'success' => true,
            'data' => $licenses
        ]);
    }

    /**
     * Cập nhật danh sách license được phép
     */
    public function updateAllowedLicenses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'licenses' => 'required|array',
            'licenses.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $this->licenseService->updateAllowedLicenses($request->licenses);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật danh sách license thành công'
        ]);
    }

    /**
     * Tạo báo cáo license
     */
    public function generateReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scan_id' => 'required|string',
            'format' => 'nullable|in:json,pdf,csv',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $report = $this->licenseService->generateReport(
            $request->scan_id,
            $request->format ?? 'json'
        );

        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }

    /**
     * Phân tích license conflict
     */
    public function analyzeConflicts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'licenses' => 'required|array',
            'licenses.*' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $conflicts = $this->licenseService->analyzeConflicts($request->licenses);

        return response()->json([
            'success' => true,
            'data' => $conflicts
        ]);
    }

    /**
     * Gợi ý license phù hợp
     */
    public function suggestLicense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_type' => 'required|string|in:open_source,commercial,saas,education',
            'want_attribution' => 'nullable|boolean',
            'allow_modification' => 'nullable|boolean',
            'commercial_use' => 'nullable|boolean',
            'copyleft' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $suggestions = $this->licenseService->suggestLicense([
            'project_type' => $request->project_type,
            'want_attribution' => $request->want_attribution ?? false,
            'allow_modification' => $request->allow_modification ?? true,
            'commercial_use' => $request->commercial_use ?? true,
            'copyleft' => $request->copyleft ?? false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }

    /**
     * Quét license từ file
     */
    public function scanFromFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:json,txt|max:10240',
            'file_type' => 'required|in:composer_lock,package_lock,requirements,pom_xml,go_mod',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');
        $content = file_get_contents($file->getPathname());

        $result = $this->licenseService->scanFromFile($content, $request->file_type);

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Lấy thông tin license
     */
    public function getLicenseInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $info = $this->licenseService->getLicenseInfo($request->license_id);

        return response()->json([
            'success' => true,
            'data' => [
                'name' => $info['name'],
                'spdx_id' => $info['spdx_id'],
                'description' => $info['description'],
                'permissions' => $info['permissions'],
                'conditions' => $info['conditions'],
                'limitations' => $info['limitations'],
                'url' => $info['url'] ?? null,
            ]
        ]);
    }
}