<?php
require_once('Application.php');

/**
 *
 * @author Tobias Sarnowski
 */
class DefaultApplication implements Application {

    public function getWelcomeMessage() {
        return "Hello World";
    }

}
