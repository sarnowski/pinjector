<?php
require_once('Application.php');

/**
 *
 * @author Tobias Sarnowski
 */
class DefaultApplication implements Application {

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param Helper $helper
     * @return void
     */
    function __construct(Helper $helper) {
        $this->helper = $helper;
    }

    public function getWelcomeMessage() {
        return $this->helper->generateHello("World");
    }

}
