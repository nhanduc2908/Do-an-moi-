<?php

namespace App\Http\Controllers\Api\V1\Module04_PasswordSecurity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\PasswordStrengthService;

class PasswordStrengthController extends Controller
{
    protected $strengthService;

    public function __construct(PasswordStrengthService $strengthService)
    {
        $this->strengthService = $strengthService;
    }

    /**
     * Đánh giá độ mạnh mật khẩu
     */
    public function checkStrength(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->strengthService->analyze($request->password);

        return response()->json([
            'success' => true,
            'data' => [
                'score' => $result['score'],
                'strength' => $result['strength'], // weak, fair, good, strong, excellent
                'crack_time' => $result['crack_time'],
                'feedback' => $result['feedback'],
                'suggestions' => $result['suggestions'],
            ]
        ]);
    }

    /**
     * Tạo mật khẩu mạnh
     */
    public function generate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'length' => 'nullable|integer|min:12|max:64',
            'include_uppercase' => 'nullable|boolean',
            'include_lowercase' => 'nullable|boolean',
            'include_numbers' => 'nullable|boolean',
            'include_symbols' => 'nullable|boolean',
            'exclude_similar' => 'nullable|boolean',
            'exclude_ambiguous' => 'nullable|boolean',
        ]);

        $password = $this->strengthService->generatePassword([
            'length' => $request->length ?? 16,
            'include_uppercase' => $request->include_uppercase ?? true,
            'include_lowercase' => $request->include_lowercase ?? true,
            'include_numbers' => $request->include_numbers ?? true,
            'include_symbols' => $request->include_symbols ?? true,
            'exclude_similar' => $request->exclude_similar ?? true,
            'exclude_ambiguous' => $request->exclude_ambiguous ?? true,
        ]);

        $strength = $this->strengthService->analyze($password);

        return response()->json([
            'success' => true,
            'data' => [
                'password' => $password,
                'strength' => $strength
            ]
        ]);
    }

    /**
     * Tạo mật khẩu dễ nhớ (passphrase)
     */
    public function generatePassphrase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'word_count' => 'nullable|integer|min:3|max:8',
            'separator' => 'nullable|string|max:2',
            'capitalize' => 'nullable|boolean',
            'include_number' => 'nullable|boolean',
        ]);

        $passphrase = $this->strengthService->generatePassphrase([
            'word_count' => $request->word_count ?? 4,
            'separator' => $request->separator ?? '-',
            'capitalize' => $request->capitalize ?? true,
            'include_number' => $request->include_number ?? true,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'passphrase' => $passphrase,
                'strength' => $this->strengthService->analyze($passphrase)
            ]
        ]);
    }
}