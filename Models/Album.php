<?php
/**
 * Created by PhpStorm.
 * User: tuanp
 * Date: 06/02/2017
 * Time: 13:20
 */

namespace Database\Models;
require_once('../Core/Model.php');


class Album extends Model
{
    protected $table = 'albums';

    public function isExisted()
    {
        $result = $this->where('name', "name = '$this->name'");
        return (count($result) != 0);
    }

    public function getByUser($user_id) {
        $rows = parent::findByColumn("user_id", $user_id);
        return $rows;
    }

    public function isOwned($user_id) {
        if ($this->user_id == $user_id) {
            return true;
        } else {
            return false;
        }
    }

    public function assignToCircle($album_id, $circle_id) {
        $sql = "INSERT INTO circle_album (circle, album) VALUES ($circle_id, $album_id)";
        //$sql = "INSERT INTO circle_album (circle, album) VALUES (581, 141)";
        $result = parent::raw($sql);
        echo $result;
    }

    public function createAlbumForCircle($circle_id) {
        $this->save();
        $sql = "SELECT LAST_INSERT_ID() as latest_id";
        $result = parent::raw($sql);
        $latest_album = intval($result[0]->latest_id);

        $this->assignToCircle($latest_album, $circle_id);
    }
}