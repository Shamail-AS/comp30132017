<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 10/02/2017
 * Time: 11:19
 */

namespace Database\Models;
require_once('../Core/Model.php');


class Tag extends Model
{
    protected $table = 'tags';

    function utf8ize($d) {
        if (is_array($d)) {
            foreach ($d as $k => $v) {
                $d[$k] = utf8ize($v);
            }
        } else if (is_string ($d)) {
            return utf8_encode($d);
        }
        return $d;
    }

    public function addNewTag($image_id) {
        $this->save();
        $sql = "SELECT LAST_INSERT_ID() as latest_id";
        $result = parent::raw($sql);
        $latest_tag = intval($result[0]->latest_id);

        //return var_dump($result);
        $sql2 = "INSERT INTO image_tags (image, tag) VALUES ($image_id, $latest_tag)";
        $result2 = parent::raw($sql2);
        //echo $latest_tag;
    }

    public function getIDbyKey($key, $image_id) {
        $sql = "SELECT t.id as id FROM tags t, image_tags i_t WHERE t.id = i_t.tag AND i_t.image = '$image_id' AND t.key = '$key'";
        $result = parent::raw($sql);
        return $result[0]->id;
    }

    public function removeTag($key, $image_id) {
        $id = $this->getIDbyKey($key, $image_id);
        //echo $id;
        $sql = "DELETE FROM image_tags WHERE `tag` = '$id'";
        $result = parent::raw($sql);
        $sql2 = "DELETE FROM tags WHERE `id` = '$id'";
        $result = parent::raw($sql2);
    }
}