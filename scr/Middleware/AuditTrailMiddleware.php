<?php

namespace Rollswan\AuditTrail\Middleware;

use Closure;
use Rollswan\AuditTrail\Traits\ActivityLog;

class AuditTrailMiddleware
{
    use ActivityLog;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $description = null)
    {
        // No published config file found
        if (!is_file(config_path('audit-trail.php'))) {
            return  response()->json([
                'message' => 'No publish audit-trail config file found.',
                'result' => []
            ], 400);
        }

        // Log user activity
        $this->logActivity($description);

        return $next($request);
    }
}
