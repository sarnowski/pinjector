<?php
require_once('Application.php');

/**
 *
 * @author Tobias Sarnowski
 */
class TestApplication implements Application {

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param Helper $helper !alternative the alternative helper implementation
     * @return void
     */
    function __construct($helper) {
        $this->helper = $helper;
    }


    public function getWelcomeMessage() {
        return $this->helper->generateHello("World");
    }

    public function __toString() {
        return '{TestApplication}';
    }
}
