
<?php
use Closure;

class CheckPermissions
{
    public function handle($request, Closure $next, ...$permissions)
    {
        $userPermissions = auth()->user()->permissions;

        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }
}
?>
