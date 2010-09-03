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
        $binder->bind('Helper')->to('DefaultHelper')->inRequestScope();

        $helper = new DefaultHelper();
        $helper->setPrefix('Hi ');
        $binder->bind('Helper')->annotatedWith('alternative')->toInstance($helper);

        $binder->bind('Application')->to('DefaultApplication')->inRequestScope();
    }
}
