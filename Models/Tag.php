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
        $sql = "SELECT LAST_INSERT_ID() as id";
        $result = parent::raw($sql);
        $latest_tag = intval($result[0]["id"]);

        //return var_dump($result);
        $sql2 = "INSERT INTO image_tags (image, tag) VALUES ($image_id, $latest_tag)";
        $result = parent::raw($sql2);
        echo ($latest_tag);
    }

    public function getIDbyKey($key) {
        $sql = "SELECT `id` FROM tags WHERE `key` = '$key'";
        $result = parent::raw($sql);
        return $result[0]['id'];
    }

    public function removeTag($key) {
        $id = $this->getIDbyKey($key);
        echo $id;
        parent::deleteByColumn("image_tags", "tag", $id);
        parent::delete("tags", $id);
    }
}