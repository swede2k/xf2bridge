<?php

namespace swede2k\XF2Bridge\Middleware;

use Closure;
use swede2k\XF2Bridge\XF2Bridge;

class XenAuthAdminMiddleware
{
    private $xenforo;

    public function __construct(XF2Bridge $xenforo)
    {
        $this->xenforo = $xenforo;
    }

    public function handle($request, Closure $next)
    {
        $baseUrl = config('xenforobridge.xenforo_base_url_path');

        if(!$this->xenforo->isAdmin() AND ! $this->xenforo->isBanned())
        {
            return redirect($baseUrl);
        }

        return $next($request);
    }
}
