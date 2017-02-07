<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 01/02/2017
 * Time: 11:12
 */

namespace Database\Models;

require_once('../Core/Model.php');

class User extends Model
{
    protected $table = 'users';

    public function isRegistered()
    {
        $result = $this->where('username', "username = '$this->username'");
        return (count($result) != 0);
    }

    public function getFriends()
    {

    }

    public function isFriendsWith($user)
    {

    }
}