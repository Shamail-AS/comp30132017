<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 01/02/2017
 * Time: 11:12
 */

namespace Database\Models;

require_once('../Core/Model.php');
require_once('../Models/User.php');
require_once('../Models/Friendship.php');

class Invite extends Model
{
    protected $table = 'friend_invites';

    public function sender($user = null)
    {
        if ($user != null) {
            $this->user1 = $user->id;
        } else {
            $user = new User();
            return $user->find($this->user1);
        }

    }

    public function receiver($user = null)
    {
        if ($user != null) {
            $this->user2 = $user->id;
        } else {
            $user = new User();
            return $user->find($this->user2);
        }
    }

    public function accept()
    {
        $this->status = 'accepted';
        $friendship = new Friendship();
        $friendship->makeFriends($this->user1, $this->user2);
        $this->save();
    }

    public function send()
    {
        $this->save();
    }

    public function cancel()
    {

        $this->status = 'canceled';
        $this->save();
    }

    public function reject()
    {
        $this->status = 'rejected';
        $this->save();
    }

    public function act($action)
    {
        if ($action == 'accept') {
            $this->accept();
            return;
        } else if ($action == 'reject') {
            $this->reject();
            return;
        } else if ($action == 'cancel') {
            $this->cancel();
            return;
        }
    }

    public function allBy($user)
    {
        $invites = $this->where(null, "user1 = $user->id");
        return $invites;
    }

    public function allFor($user)
    {
        $invites = $this->where(null, "user2 = $user->id");
        return $invites;
    }

    public function existsBetween($user1, $user2, $status)
    {
        $invites = $this->where(null, "((user1 = $user1->id and user2 = $user2->id) or (user1 = $user2->id and user2 = $user1->id)) and status = '$status'");
        return count($invites) > 0;
    }

    public function isSentBy($user)
    {
        return $this->user1 == $user->id;
    }
}