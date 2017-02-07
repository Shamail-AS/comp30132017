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
}