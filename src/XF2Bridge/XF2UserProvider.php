<?php

namespace swede2k\XF2Bridge;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Auth\AuthenticationException;

class XF2UserProvider implements UserProvider
{
    protected $xenforo;

    public function __construct(XF2Bridge $xenforo)
    {
        $this->xenforo = $xenforo;
    }

    public function retrieveById($identifier)
    {
        return \XF::finder('XF:User')->where('user_id', $identifier)->fetchOne();
    }

    public function retrieveByToken($identifier, $token)
    {
        throw new AuthenticationException('Remember Tokens not implemented by '.get_class($this));
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        throw new AuthenticationException('Remember Tokens not implemented by '.get_class($this));
    }

    public function retrieveByCredentials(array $credentials)
    {
        throw new AuthenticationException('Cannot Retrieve By Credentials '.get_class($this));
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        throw new AuthenticationException('Cannot Validate Credentials '.get_class($this));
    }

}