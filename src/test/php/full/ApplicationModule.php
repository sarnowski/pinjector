<?php
require_once('Binder.php');
require_once('Module.php');
require_once('Application.php');
require_once('DefaultApplication.php');

/**
 *
 * @author Tobias Sarnowski
 */
class ApplicationModule implements Module {

    public function configure(Binder $binder) {
        $binder->bind('Application')->to('DefaultApplication');
    }
}
