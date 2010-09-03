<?php
require_once('Binder.php');
require_once('Module.php');
require_once('Application.php');
require_once('DefaultApplication.php');
require_once('DefaultHelper.php');
require_once('Helper.php');

/**
 *
 * @author Tobias Sarnowski
 */
class ApplicationModule implements Module {

    public function configure(Binder $binder) {
        $binder->bind('Helper')->to('DefaultHelper');
        $binder->bind('Application')->to('DefaultApplication');
    }
}
