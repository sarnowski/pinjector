<?php
require_once('Helper.php');

/**
 *
 * @author Tobias Sarnowski
 */ 
class DefaultHelper implements Helper {

    private $prefix = 'Hello ';

    function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function generateHello($name) {
        return $this->prefix.$name;
    }
}
