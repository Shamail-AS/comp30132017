<?php
/**
 * Created by PhpStorm.
 * User: Shamail
 * Date: 06/02/2017
 * Time: 15:29
 */

namespace Http\Session;


class SessionManager
{
    public function start()
    {
        if (session_status() != PHP_SESSION_DISABLED) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
                $_SESSION['errors'] = [];
            }
            return $this;
        } else
            return false;
    }

    public function __get($key)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        } else return null;
    }

    public function __set($name, $value)
    {
        if (session_status() == PHP_SESSION_ACTIVE)
            $_SESSION[$name] = $value;
        else {
            return $this->start();
        }
    }

    public function hasErrors()
    {
        return !empty($_SESSION['errors']);
    }

    public function errors()
    {
        var_dump(session_status());
        if (session_status() == PHP_SESSION_ACTIVE) {
            return $_SESSION['errors'];
        } else return false;

    }

    public function addError($key, $value)
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $_SESSION['errors'][$key] = $value;
        }
    }

    public function getError($key)
    {
        if (session_status() == PHP_SESSION_ACTIVE && !empty($_SESSION['errors'][$key])) {
            return $_SESSION['errors'][$key];
        } else return false;
    }

    public function clean()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            $_SESSION['errors'] = [];
        }
    }

    public function redirect($dest)
    {
        if ($this->hasErrors()) {
            if (strpos($dest, '.php')) {
                header($dest);
                return;
            } else {
                header($dest . '.php');
                return;
            }
        }
        if (strpos($dest, '.php')) {
            header('Location: ' . $dest);
            return;
        } else {
            header('Location:' . $dest . '.php');
            return;
        }
    }

    public function dd($var)
    {
        var_dump($var);
        die();
    }

    public function end()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_unset();
            session_abort();
            session_destroy();
        }
    }
}