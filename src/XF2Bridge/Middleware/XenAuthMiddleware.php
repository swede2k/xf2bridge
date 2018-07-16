<?php namespace culv3r\XF2Bridge\Middleware;

use Closure;
use Config;
use Session;
use Redirect;
use culv3r\XF2Bridge\XF2Bridge;

class XenAuthMiddleware {

    /**
     * stores Xenforo Bridge class
     * @var XF2Bridge\XF2Bridge
     */
    private $xenforo;

    /**
     * Construct Middleware Class
     *
     * @param \culv3r\XF2Bridge\XF2Bridge $xenforo
     */
    public function __construct(XF2Bridge $xenforo)
    {
        $this->xenforo = $xenforo;
    }

    /**
     * Run the request filter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $xenBaseUrl = config('xenforobridge.xenforo_base_url_path');

        if(!$this->xenforo->isLoggedIn() AND ! $this->xenforo->isBanned())
        {
            Session::put('loginRedirect', $request->url());
            return Redirect::to($xenBaseUrl.'login');
        }

        return $next($request);
    }

}