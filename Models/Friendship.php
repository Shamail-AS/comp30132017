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
    const SIMILARITY_THRESHOLD = 2;
    public function areFriends($user1, $user2)
    {
        $friendship = $this->where(null, "user1 = $user1->id AND user2 = $user2->id");
        return count($friendship) > 0;
    }


    public function allFriends($user)
    {
        return parent::where("user2", "user1 = $user->id");
    }

    public function makeFriends($user1_id, $user2_id)
    {
        //keep a symmetrical relationship.
        //otherwise, complex queries needed for mutual friend search
        $friendship = new Friendship();
        $friendship->user1 = $user1_id;
        $friendship->user2 = $user2_id;
        $friendship->save();
        $friendship = new Friendship();
        $friendship->user2 = $user1_id;
        $friendship->user1 = $user2_id;
        $friendship->save();
    }

    public function suggestionsFor($user)
    {
        $friends = $user->getFriends();
        $friendIds = array_map(function ($friend) {
            return $friend->id;
        }, $friends);
        //var_dump($friends);
        $userManager = new User();
        if (count($friends) > 0)
            $directory_users = $userManager->whereNotIn(null, 'id', $friendIds); //candidates
        else
            $directory_users = $userManager->all(); //candidates

        array_push($friends, $user); //the candidates must be similar to these

        //array_udiff($directory_users,$friends,'compByIds');
        $similarUsers = [];

        foreach ($directory_users as $candidate) { //candidates
            $similarity = 0;
            foreach ($friends as $friend) { //friends + me

                //friends + me will also be in the directory_users of users. Skip those
                if ($friend->id == $candidate->id) continue;
                $similarity += $this->getSimilarity($candidate, $friend);
            }
            $candidate->similarity = $similarity;
            //var_dump($candidate);
            if ($similarity > self::SIMILARITY_THRESHOLD)
                array_push($similarUsers, $candidate);
        }
        $fofs = $user->getFriendsOfFriends();
        return array_merge($similarUsers, $fofs);
    }

    public function getSimilarity($d_user, $friend)
    {
        $similarity = 0;
        if ($d_user->birthplace == $friend->birthplace) $similarity++;
        if ($d_user->work == $friend->birthplace) $similarity++;
        if ($d_user->school == $friend->school) $similarity++;
        if ($d_user->university == $friend->university) $similarity++;
        if ($d_user->sex == $friend->interested_in) $similarity++;
        if ($d_user->isSameAgeGroupAs($friend)) $similarity++;
        return $similarity;
    }
}