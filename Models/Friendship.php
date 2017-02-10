<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 10/02/2017
 * Time: 12:49
 */

namespace Database\Models;


class Friendship extends Model
{
    protected $table = "friends";

    public function areFriends($user1, $user2)
    {
        $friendship = $this->where(null, "(user1 = $user1->id AND user2 = $user2->id) OR (user1 = $user2->id AND user2 = $user1->id)");
        return count($friendship) > 0;
    }

    public function allFriends($user)
    {
        return parent::where("user2", "user1 = $user->id");
    }
}