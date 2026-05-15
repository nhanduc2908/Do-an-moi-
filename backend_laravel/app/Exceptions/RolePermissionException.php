<?php

namespace App\Exceptions;

use Exception;

class RolePermissionException extends Exception
{
    protected $message = 'Bạn không có quyền thực hiện hành động này';
    protected $code = 403;

    protected $userId;
    protected $role;
    protected $requiredPermission;
    protected $action;

    public function __construct($message = null, $userId = null, $requiredPermission = null, $action = null)
    {
        $this->userId = $userId;
        $this->requiredPermission = $requiredPermission;
        $this->action = $action;
        
        if ($message) {
            $this->message = $message;
        }
        
        parent::__construct($this->message, $this->code);
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getRequiredPermission()
    {
        return $this->requiredPermission;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'permission_denied',
            'message' => $this->message,
            'required_permission' => $this->requiredPermission,
            'action' => $this->action,
            'code' => 'ROLE_PERMISSION_ERROR'
        ], $this->code);
    }
}