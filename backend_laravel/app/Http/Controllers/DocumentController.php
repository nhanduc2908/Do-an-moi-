<?php

namespace App\Http\Controllers;

use App\Models\Module02_Encryption\EncryptedDocument;
use App\Services\Module02_Encryption\DocumentEncryptionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    protected $documentEncryptionService;

    public function __construct(DocumentEncryptionService $documentEncryptionService)
    {
        $this->documentEncryptionService = $documentEncryptionService;
        $this->middleware('auth');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:51200',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'classification' => ['required', Rule::in(['public', 'internal', 'confidential', 'restricted', 'top_secret'])],
            'required_level' => 'nullable|integer|min:0|max:100',
            'expires_at' => 'nullable|date|after:now',
            'allowed_roles' => 'nullable|array',
            'allowed_roles.*' => 'exists:roles,name',
            'allowed_users' => 'nullable|array',
            'allowed_users.*' => 'exists:users,id',
        ]);

        $classification = $request->classification;
        $requiredLevel = $request->required_level ?? config("document_security.classifications.{$classification}.level", 0);
        
        $document = $this->documentEncryptionService->encryptDocument(
            $request->file('file'),
            $request->user(),
            [
                'title' => $request->title,
                'description' => $request->description,
                'classification' => $classification,
                'required_level' => $requiredLevel,
                'allowed_roles' => $request->allowed_roles,
                'allowed_users' => $request->allowed_users,
                'expires_at' => $request->expires_at,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded and encrypted successfully',
            'document' => $document,
        ], 201);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $userLevel = $this->getUserLevel($user);
        
        $documents = EncryptedDocument::where('required_level', '<=', $userLevel)
            ->where('is_deleted', false)
            ->orderBy('uploaded_at', 'desc')
            ->paginate($request->per_page ?? 20);
        
        return response()->json([
            'success' => true,
            'documents' => $documents,
        ]);
    }

    public function view($id, Request $request)
    {
        $document = EncryptedDocument::findOrFail($id);
        
        $justification = $request->justification;
        
        try {
            $content = $this->documentEncryptionService->decryptDocument($document, $request->user(), $justification);
            
            return response($content)
                ->header('Content-Type', $document->mime_type)
                ->header('Content-Disposition', 'inline; filename="' . $document->original_name . '"')
                ->header('X-Document-Classification', $document->classification)
                ->header('X-Document-Level', $document->required_level);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    public function download($id, Request $request)
    {
        $document = EncryptedDocument::findOrFail($id);
        
        $justification = $request->justification;
        
        try {
            $content = $this->documentEncryptionService->decryptDocument($document, $request->user(), $justification);
            
            return response($content)
                ->header('Content-Type', $document->mime_type)
                ->header('Content-Disposition', 'attachment; filename="' . $document->original_name . '"')
                ->header('X-Document-Classification', $document->classification);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    public function delete($id, Request $request)
    {
        $document = EncryptedDocument::findOrFail($id);
        
        $userLevel = $this->getUserLevel($request->user());
        if ($userLevel < 90) {
            abort(403, 'You do not have permission to delete documents');
        }
        
        $document->is_deleted = true;
        $document->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully',
        ]);
    }

    public function share(Request $request, $id)
    {
        $document = EncryptedDocument::findOrFail($id);
        
        $request->validate([
            'user_ids' => 'nullable|array',
            'user_ids.*' => 'exists:users,id',
            'role_names' => 'nullable|array',
            'role_names.*' => 'exists:roles,name',
        ]);
        
        if ($request->has('user_ids')) {
            $document->allowed_users = array_unique(array_merge($document->allowed_users ?? [], $request->user_ids));
        }
        
        if ($request->has('role_names')) {
            $document->allowed_roles = array_unique(array_merge($document->allowed_roles ?? [], $request->role_names));
        }
        
        $document->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Document shared successfully',
        ]);
    }

    public function logs($id)
    {
        $document = EncryptedDocument::findOrFail($id);
        
        $logs = $document->accessLogs()
            ->with('user')
            ->orderBy('accessed_at', 'desc')
            ->paginate(50);
        
        return response()->json([
            'success' => true,
            'document' => $document,
            'logs' => $logs,
        ]);
    }

    private function getUserLevel($user): int
    {
        $roleLevels = [
            'super_admin' => 100,
            'admin' => 90,
            'security_manager' => 80,
            'risk_manager' => 75,
            'compliance_officer' => 70,
            'security_analyst' => 60,
            'incident_responder' => 55,
            'vulnerability_scanner' => 45,
            'auditor' => 50,
            'viewer' => 10,
        ];
        
        $role = $user->roles->first();
        return $role ? ($roleLevels[$role->name] ?? 0) : 0;
    }
}