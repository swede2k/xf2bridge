<?php

namespace swede2k\XF2Bridge;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Authenticatable;


class XF2Guard implements Guard
{
    protected $xenforo;
    protected $user;

    public function __construct(XF2Bridge $xenforo)
    {
        $this->xenforo  = $xenforo;
    }

    public function check()
    {
        return ! is_null($this->user());
    }

    public function guest()
    {
        return ! $this->check();
    }

    public function user()
    {
        if(! is_null($this->user))
        {
            return $this->user;
        }
        $user = null;

        if($this->xenforo->isLoggedIn())
        {
            $user = $this->xenforo->getVisitorObject();
        }
        return $this->user = $user; /** @todo Implement Authenticable */
    }

    public function id()
    {
        if($this->user())
        {
            return $this->user()->getUserId();
        }
    }

    public function validate(array $credentials = [])
    {
        // TODO: Implement validate() method.
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
        return $this;
    }
}