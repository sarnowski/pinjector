<?php
require_once('Helper.php');

/**
 *
 * @author Tobias Sarnowski
 */ 
class TestHelper implements Helper {

    private $prefix = 'Hello ';

    function setPrefix($prefix) {
        $this->prefix = $prefix;
    }

    public function generateHello($name) {
        return $this->prefix.$name;
    }

    public function __toString() {
        return '{TestHelper}';
    }
}
