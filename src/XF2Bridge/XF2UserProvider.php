<?php
/**
 * Copyright (C) Stellaron, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by George Barba <george@agenaastro.com>, September 2017
 */

namespace culv3r\XF2Bridge;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Auth\AuthenticationException;
use culv3r\XF2Bridge\User\User;

class XF2UserProvider implements UserProvider
{
    protected $xenforo;

    public function __construct(XF2Bridge $xenforo)
    {
        $this->xenforo = $xenforo;
    }

    public function retrieveById($identifier)
    {
        return new User($this->xenforo->getUserById($identifier));
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