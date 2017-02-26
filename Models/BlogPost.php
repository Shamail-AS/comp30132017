<?php
/**
 * Created by PhpStorm.
 * User: Tang Gao Jun
 * Date: 6/2/2017
 * Time: 6:07 PM
 */

namespace Database\Models;

require_once('../Core/Model.php');

class Blog_post extends Model
{
    protected $table = 'blog_post';

    public function getByUser($user_id) {
        $rows = parent::findByColumn("users", $user_id);
        return $rows;
    }

    public function deletePost($id) {
        return $this->delete($this,$id);

    }
}