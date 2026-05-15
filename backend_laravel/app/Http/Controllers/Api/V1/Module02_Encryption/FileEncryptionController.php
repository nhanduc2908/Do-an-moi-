<?php

namespace App\Http\Controllers\Api\V1\Module02_Encryption;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\FileEncryptionService;

class FileEncryptionController extends Controller
{
    protected $fileEncryption;

    public function __construct(FileEncryptionService $fileEncryption)
    {
        $this->fileEncryption = $fileEncryption;
    }

    /**
     * Upload và mã hóa file
     */
    public function uploadEncrypted(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|max:512000', // Max 500MB
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('file');
        
        $result = $this->fileEncryption->encryptAndStore(
            $file,
            auth()->id(),
            $request->password
        );

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => 'Upload và mã hóa thành công'
        ]);
    }

    /**
     * Download và giải mã file
     */
    public function downloadDecrypted($id)
    {
        $file = $this->fileEncryption->findFile($id, auth()->id());

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File không tồn tại'
            ], 404);
        }

        $decryptedContent = $this->fileEncryption->decryptAndDownload($file);

        return response()->streamDownload(function() use ($decryptedContent) {
            echo $decryptedContent;
        }, $file->original_name);
    }

    /**
     * Danh sách file đã mã hóa
     */
    public function listFiles(Request $request)
    {
        $files = $this->fileEncryption->getUserFiles(auth()->id(), $request->per_page ?? 20);

        return response()->json([
            'success' => true,
            'data' => $files
        ]);
    }

    /**
     * Xóa file mã hóa
     */
    public function deleteFile($id)
    {
        $result = $this->fileEncryption->deleteFile($id, auth()->id());

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Xóa thành công' : 'Xóa thất bại'
        ]);
    }

    /**
     * Stream mã hóa (upload lớn)
     */
    public function streamEncrypt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'chunk' => 'required|string',
            'chunk_index' => 'required|integer',
            'total_chunks' => 'required|integer',
            'file_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->fileEncryption->processChunk(
            $request->chunk,
            $request->chunk_index,
            $request->total_chunks,
            $request->file_name,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
}