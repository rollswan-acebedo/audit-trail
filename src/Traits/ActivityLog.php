<?php

namespace Rollswan\AuditTrail\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Jaybizzle\LaravelCrawlerDetect\Facades\LaravelCrawlerDetect as Crawler;
use Rollswan\AuditTrail\Models\AuditTrail;

trait ActivityLog
{
    /**
     * Log user activity
     *
     * @param string|null $description
     */
    public function logActivity($description = null)
    {
        // Prepare the data
        $userId = null;
        $userType = config('audit-trail.user_types.guest');

        // Check authenticated user
        if (Auth::check()) {
            $userType = config('audit-trail.user_types.registered');
            $userIdField = config('audit-trail.user_id');
            $userId = Request::user()->{$userIdField};
        }

        // Check web crawler
        if (Crawler::isCrawler()) {
            $userType = config('audit-trail.user_types.crawler');

            if (is_null($description)) {
                $description = $userType . ' ' . config('audit-trail.method.crawled') . ' ' . Request::fullUrl();
            }
        }

        // No found description
        if (!$description) {
            $method = strtolower(Request::method());
            $verb = config('audit-trail.method.' . $method);
            $description = $verb . ' ' . Request::path();
        }

        // Create new audit trail
        AuditTrail::create([
            'user_id' => $userId,
            'user_type' => $userType,
            'description' => $description,
            'route' => Request::fullUrl(),
            'method_type' => Request::method(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('user-agent')
        ]);
    }
}
