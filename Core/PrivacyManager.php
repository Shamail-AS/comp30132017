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
    public static function canViewImage($viewer, $user)
    {

    }
}