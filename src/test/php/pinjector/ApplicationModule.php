<?php
require_once('pinjector/Binder.php');
require_once('pinjector/Module.php');
require_once('Application.php');
require_once('TestApplication.php');
require_once('TestHelper.php');
require_once('TestInterceptor.php');
require_once('Helper.php');

/**
 *
 * @author Tobias Sarnowski
 */
class ApplicationModule implements Module {

    public function configure(Binder $binder) {
        $binder->bind('Helper')->to('TestHelper')->inRequestScope();

        $helper = new TestHelper();
        $helper->setPrefix('Hi ');
        $binder->bind('Helper')->annotatedWith('alternative')->toInstance($helper);

        $binder->bind('Application')->to('TestApplication')->inRequestScope();

        $binder->bind('TestInterceptor')->inRequestScope();
        $binder->interceptWith('TestInterceptor');
    }
}
