<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 06/02/2017
 * Time: 13:20
 */

namespace Database\Models;
require_once('../Core/Model.php');


class Image extends Model
{
    protected $table = 'images';
    function getTags() {
        // ' ' sucks
        $query = "SELECT t.id as id, t.key as text, t.value FROM images i, tags t, image_tags i_t WHERE i.id = i_t.image AND t.id = i_t.tag AND i_t.image = $this->id";
        $rows = parent::raw($query);
        return $rows;
    }

    function isOwnedBy($user_id) {
    }
}