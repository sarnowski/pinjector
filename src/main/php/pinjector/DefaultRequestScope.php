<?php
require_once('Scope.php');

/**
 *
 * @author Tobias Sarnowski
 */ 
class DefaultRequestScope implements Scope {

    private $instances = array();

    public function getScope($key) {
        if (!isset($this->instances[$key])) {
            return null;
        }
        return $this->instances[$key];
    }

    public function putScope($key, $value) {
        $this->instances[$key] = $value;
    }
}
