<?php
require_once('Helper.php');

/**
 *
 * @author Tobias Sarnowski
 */ 
class DefaultHelper implements Helper {

    public function generateHello($name) {
        return "Hello $name";
    }
}
