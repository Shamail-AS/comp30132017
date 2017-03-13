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

    public function getByID($id) {
        $result = parent::findByColumn("id", $id);
        return $result[0];
    }

    public function getByUser($user_id) {
        $rows = parent::findByColumn("user_id", $user_id);
        return $rows;
    }

    public function getAlbumByCircleID($circle_id) {
        $sql = "SELECT DISTINCT a.id, a.user_id, a.name FROM circle_album as c, albums as a WHERE c.circle = " . $circle_id . " AND c.album = a.id";
        $result = parent::raw($sql);
        return $result;
    }

    public function owner()
    {
        $u = new User();
        return $u->find($this->user_id);
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

    public function getAssignedCircle($album_id) {
        $sql = "SELECT circle FROM circle_album WHERE `album` = " . $album_id;
        $result = parent::raw($sql);
        return $result;
    }

    public function delWithCircle($album_id, $circle_id) {
        $sql = "DELETE FROM circle_album WHERE `circle` = " . $circle_id . " AND `album` = " .$album_id ;
        $result = parent::raw($sql);
        echo $result;
    }

    public function remove($id) {
        $sql = "DELETE FROM circle_album WHERE `album` = '$id'";
        $sql2 = "DELETE FROM albums WHERE `id` = '$id'";
        $sql3 = "DELETE FROM images WHERE `album_id` = '$id'";
        parent::raw($sql);
        parent::raw($sql2);
        parent::raw($sql3);
    }
}