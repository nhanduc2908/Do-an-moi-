<?php

namespace App\Services\Module28_SystemAdmin;

use App\Models\Module01_IAM\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserManagementService
{
    public function createUser($data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'department' => $data['department'] ?? null,
            'status' => 'active'
        ]);
        
        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        }
        
        $this->sendWelcomeEmail($user, $data['password']);
        
        return $user;
    }

    public function updateUser($userId, $data)
    {
        $user = User::findOrFail($userId);
        
        $user->update(array_filter([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'department' => $data['department'] ?? $user->department,
            'status' => $data['status'] ?? $user->status
        ]));
        
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
            $user->save();
        }
        
        if (isset($data['role'])) {
            $user->syncRoles([$data['role']]);
        }
        
        return $user;
    }

    public function suspendUser($userId, $reason)
    {
        $user = User::findOrFail($userId);
        $user->status = 'suspended';
        $user->save();
        
        $this->logUserAction($userId, 'suspended', $reason);
        
        return $user;
    }

    public function activateUser($userId)
    {
        $user = User::findOrFail($userId);
        $user->status = 'active';
        $user->save();
        
        $this->logUserAction($userId, 'activated');
        
        return $user;
    }

    public function deleteUser($userId, $transferTo = null)
    {
        $user = User::findOrFail($userId);
        
        if ($transferTo) {
            // Transfer resources to another user
            $this->transferUserResources($userId, $transferTo);
        }
        
        $user->delete();
        
        return true;
    }

    protected function sendWelcomeEmail($user, $plainPassword)
    {
        Mail::send('emails.welcome', [
            'user' => $user,
            'password' => $plainPassword
        ], function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Welcome to Security Platform');
        });
    }

    protected function transferUserResources($fromUserId, $toUserId)
    {
        // Transfer owned resources
    }

    protected function logUserAction($userId, $action, $details = null)
    {
        \Log::info('User management action', [
            'user_id' => $userId,
            'action' => $action,
            'performed_by' => auth()->id(),
            'details' => $details,
            'timestamp' => now()
        ]);
    }

    public function getUserAuditLog($userId)
    {
        // Return user action history
        return [];
    }
}