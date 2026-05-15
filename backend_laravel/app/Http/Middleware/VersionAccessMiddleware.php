<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Version;
use App\Exceptions\VersionAccessDeniedException;

class VersionAccessMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $versionId = $request->route('versionId') ?? $request->input('version_id');
        
        if (!$versionId) {
            return $next($request);
        }

        $version = Version::find($versionId);
        
        if (!$version) {
            throw new VersionAccessDeniedException('Version not found', $versionId);
        }

        // Kiểm tra version có bị khóa không
        if ($version->is_locked && !$request->user()->hasPermission('unlock_version')) {
            throw new VersionAccessDeniedException(
                'Version is locked. Please request access.',
                $versionId,
                $request->user()?->id
            );
        }

        // Kiểm tra quyền truy cập version
        if (!$request->user()->canAccessVersion($version)) {
            throw new VersionAccessDeniedException(
                'You do not have permission to access this version',
                $versionId,
                $request->user()?->id,
                $version->required_role
            );
        }

        return $next($request);
    }
}