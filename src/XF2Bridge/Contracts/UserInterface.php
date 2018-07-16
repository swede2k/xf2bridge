<?php namespace culv3r\XF2Bridge\Contracts;

interface UserInterface
{

    public function getUserById($id);

    public function getUserByUsername($name);

    public function getUserByEmail($email);
}