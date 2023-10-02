<?php
// app/Http/Middleware/IPAccessControl.php
namespace App\Http\Middleware;

use Closure;

class IPAccessControl
{
    // List of allowed IP addresses (modify this as per your requirements)
    private $allowedIPs = [
        '127.0.0.1', // Example: Allow localhost
        '102.89.32.29', // Example: Allow a specific IP
        // Add more allowed IPs as needed...
    ];

    public function handle($request, Closure $next)
    {
        $clientIP = $request->ip();

        // if (!in_array($clientIP, $this->allowedIPs)) {
        //     // IP address is not allowed, return a 403 Forbidden response
        //     return response()->json(['error' => 'Access denied from using this system.'], 403);
        // }

        // IP address is allowed, proceed to the next middleware or route
        return $next($request);
    }
}
?>