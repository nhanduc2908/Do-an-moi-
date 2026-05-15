<?php

namespace App\Http\Controllers\Api\V1\Module28_SystemAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\LanguageService;

class LanguageController extends Controller
{
    protected $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    /**
     * Languages
     */
    public function languages()
    {
        $languages = $this->languageService->getAvailableLanguages();

        return response()->json([
            'success' => true,
            'data' => $languages
        ]);
    }

    /**
     * Current language
     */
    public function currentLanguage()
    {
        $current = $this->languageService->getCurrentLanguage();

        return response()->json([
            'success' => true,
            'data' => $current
        ]);
    }

    /**
     * Switch language
     */
    public function switchLanguage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'locale' => 'required|string|in:en,vi,zh,ja,ko',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->languageService->setLanguage($request->locale);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => "Language switched to {$request->locale}"
        ]);
    }

    /**
     * Translations
     */
    public function translations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'locale' => 'nullable|string',
            'namespace' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $translations = $this->languageService->getTranslations(
            $request->locale,
            $request->namespace
        );

        return response()->json([
            'success' => true,
            'data' => $translations
        ]);
    }

    /**
     * Update translations
     */
    public function updateTranslations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'locale' => 'required|string',
            'translations' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->languageService->updateTranslations(
            $request->locale,
            $request->translations
        );

        return response()->json([
            'success' => true,
            'message' => 'Translations updated'
        ]);
    }

    /**
     * Missing translations
     */
    public function missingTranslations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'locale' => 'required|string',
            'base_locale' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $missing = $this->languageService->getMissingTranslations(
            $request->locale,
            $request->base_locale ?? 'en'
        );

        return response()->json([
            'success' => true,
            'data' => $missing
        ]);
    }

    /**
     * Sync translations
     */
    public function syncTranslations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'source_locale' => 'required|string',
            'target_locales' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->languageService->syncTranslations(
            $request->source_locale,
            $request->target_locales
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Translations synced'
        ]);
    }
}