<?php
require_once('Scope.php');

/**
 *
 * @author Tobias Sarnowski
 */ 
class DefaultSessionScope implements Scope {

    public function getScope($key) {
        if (!isset($_SESSION[$key])) {
            return null;
        }
        return $_SESSION[$key];
    }

    public function putScope($key, $value) {
        $_SESSION[$key] = $value;
    }
}
