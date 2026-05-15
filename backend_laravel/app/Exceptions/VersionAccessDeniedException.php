<?php

namespace App\Exceptions;

use Exception;

class VersionAccessDeniedException extends Exception
{
    protected $message = 'Bạn không có quyền truy cập phiên bản này';
    protected $code = 403;

    protected $versionId;
    protected $userId;
    protected $requiredRole;

    public function __construct($message = null, $versionId = null, $userId = null, $requiredRole = null)
    {
        $this->versionId = $versionId;
        $this->userId = $userId;
        $this->requiredRole = $requiredRole;
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getVersionId()
    {
        return $this->versionId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRequiredRole()
    {
        return $this->requiredRole;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'version_access_denied',
            'message' => $this->message,
            'version_id' => $this->versionId,
            'required_role' => $this->requiredRole,
            'code' => 'VERSION_ACCESS_DENIED'
        ], $this->code);
    }
}