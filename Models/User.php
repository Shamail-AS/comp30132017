<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 01/02/2017
 * Time: 11:12
 */

namespace Database\Models;


use Database\Connection\DB;

class User extends DB
{

    private $data = [];
    public function find($id){
        return parent::select('users',null,"ID=$id");
    }
    public function _set(){
        //TODO: IMPLEMENT MAGIC METHOD SETTING OF PROPERTIES
    }

    public function _get(){
        //TODO: IMPLEMENT MAGIC METHOD GETTING OF PROPERTIES
    }

    public function save(){
        //TODO: COMMIT THE USER DATA INTO THE DATABASE (UPDATE IF EXISTS)
    }

}