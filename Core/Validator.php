<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 07/02/2017
 * Time: 12:51
 */

namespace Http\Forms;


class Validator
{
    public function validateUserRegistrationData($post_data)
    {
        $errors = [];
        $messages = [
            'name' => 'Name is missing',
            'email' => 'Email is missing',
            'username' => 'Username is missing',
            'password' => 'Password is missing',
            'conf_password' => 'Password confirmation is missing',
            'match' => 'Passwords don\'t match',
        ];
        foreach ($post_data as $key => $value) {
            if (empty($post_data[$key])) {
                $errors[$key] = $messages[$key];
            }
        }
        if (!empty($post_data['password']) &&
            !empty($post_data['conf_password']) &&
            strcmp($post_data['password'], $post_data['conf_password']) != 0
        ) {
            $errors['match'] = $messages['match'];
        }
        return $errors;
    }

    public function validateUserLoginData($post_data)
    {
        $errors = [];
        $messages = [

            'username' => 'username is missing',
            'password' => 'password is missing',
        ];
        foreach ($post_data as $key => $value) {
            if (empty($post_data[$key])) {
                $errors[$key] = $messages[$key];
            }
        }
        return $errors;
    }

    public function validateUserProfileData($post_data)
    {
        $errors = [];
        $messages = [
            'name' => 'Invalid or missing name',
            'email' => 'Invalid or missing email',
            'sex' => 'Invalid or missing sex',
            'interest' => 'Invalid or missing interest',
            'birthplace' => 'Invalid or missing birthplace',
            'work' => 'Invalid or missing workplace',
            'school' => 'Invalid or missing school',
            'dob' => 'Invalid or missing dob',
            'university' => 'Invalid or missing university',
        ];
        foreach ($post_data as $key => $value) {
            if (empty($post_data[$key])) {
                $errors[$key] = $messages[$key];
            }
        }

        return $errors;
    }
    public function validateImage($files_data){
        $errors = [];
        $messages = [
            'size' => 'Invalid size',
            'format' => 'Invalid format',
        ];
        $extensions = ['jpg', 'jpeg', 'png', 'img', 'gif'];
        foreach ($files_data as $file_name => $file_attributes) {
            if($file_attributes['size'] > 500000){
                $errors[$file_name]['size'] = $messages['size'];
            }
            $ext = explode('.',$file_attributes['name'])[1];

            if(!array_key_exists($ext,$extensions)){
                $errors[$file_name]['format'] = $messages['format'];
            }
       }
       return $errors;
    }

    public function validateUserProfileData($post_data)
    {
        $errors = [];
        $messages = [
            'name' => 'Name is invalid',
            'email' => 'Email is invalid',
            'birthplace' => 'Birthplace is invalid',
            'work' => 'Work is invalid',
            'school' => 'School is invalid',
            'sex' => 'Sex is invalid',

            'dob' => 'Date of birth is invalid',
            'university' => 'University is invalid',

        ];
        if (!($post_data['selSex'] == "Female" || $post_data['selSex'] == "Male")) {
            $errors['sex'] = $messages['sex'];
        }
        foreach ($post_data as $key => $value) {
            if (!filter_var(filter_var($post_data[$key], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) {
                $errors[$key] = $messages[$key];
            }
        }
        return $errors;
    }

    public function validateUserAdminData($post_data)
    {
        $errors = [];
        $messages = [
            'name' => 'Name is invalid',
            'email' => 'Email is invalid',
            'birthplace' => 'Birthplace is invalid',
            'work' => 'Work is invalid',
            'school' => 'School is invalid',
            'sex' => 'Sex is invalid',
            'dob' => 'Date of birth is invalid',
            'university' => 'University is invalid',
            'usertype' => 'User type is invalid'

        ];
        if (!($post_data['selSex'] == "Female" || $post_data['selSex'] == "Male")) {
            $errors['sex'] = $messages['sex'];
        }
        if (!($post_data['selUserType'] == "ADMIN" || $post_data['selUserType'] == "USER")) {
            $errors['usertype'] = $messages['usertype'];
        }
        foreach ($post_data as $key => $value) {
            if (!filter_var(filter_var($post_data[$key], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL)) {
                $errors[$key] = $messages[$key];
            }
        }
        return $errors;
    }
}