<?php
/**
 * Created by PhpStorm.
 * User: asdfg
 * Date: 12/03/2017
 * Time: 12:39
 */

namespace Database\Core;
require_once('../Models/User.php');
require_once('../Models/Album.php');
require_once('../Models/Friendship.php');
require_once('../Models/Image.php');
require_once('../Models/BlogPost.php');

use Database\Models\User;
use Database\Models\Album;
use Database\Models\Friendship;
use Database\Models\Blog_post;

class PrivacyManager
{

    public static function canViewAlbum($viewer, $album)
    {
        $owner = $album->owner();
        $privacy_level = $album->privacy_level;
        return self::ifPossible($owner, $viewer, $privacy_level);
    }

    public static function canSearchProfile($owner, $viewer)
    {
        $privacy_level = $owner->search_privacy;
        return self::ifPossible($owner, $viewer, $privacy_level);
    }

    public static function canViewProfile($owner, $viewer)
    {
        $privacy_level = $owner->profile_privacy;
        return self::ifPossible($owner, $viewer, $privacy_level);
    }

    public static function canSendConnectionRequests($receiver, $sender)
    {
        $privacy_level = $receiver->connection_privacy;
        return self::ifPossible($receiver, $sender, $privacy_level);

    }

    public static function canAddToCircles($addee, $adder)
    {
        $privacy_level = $addee->circle_privacy;
        return self::ifPossible($addee, $adder, $privacy_level);
    }

    public static function canViewBlogPost($viewer, $blog)
    {

        $owner = $blog->owner();
        $privacy_level = $blog->privacy_level;
        return self::ifPossible($owner, $viewer, $privacy_level);
    }


    private static function ifPossible($user1, $user2, $privacy)
    {
        $f = new Friendship();
        switch ($privacy) {
            case 1: {
                return true;
                break;
            }
            case 2: {
                return $f->areFriends($user1, $user2) || $f->areFriendsOfFriend($user1, $user2);
                break;
            }
            case 3: {
                return $f->areFriends($user1, $user2);
                break;
            }
            case 4: {
                return false;
                break;
            }
            default: {
                return false;
                break;
            }
        }
    }
}