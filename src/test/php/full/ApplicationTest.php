<?php
require_once('PHPUnit/Framework.php');
require_once('DefaultKernel.php');
require_once('ApplicationModule.php');

/**
 *
 * @author Tobias Sarnowski
 */
class ApplicationTest extends PHPUnit_Framework_TestCase {

    public function testBoot() {
        $applicationModule = new ApplicationModule();
        $kernel = DefaultKernel::boot($applicationModule);

        $application = $kernel->getInstance('Application');

        $this->assertEquals('Hello World', $application->getWelcomeMessage());
    }

}
