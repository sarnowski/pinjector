<?php
require_once('PHPUnit/Framework.php');
require_once('pinjector/DefaultKernel.php');
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
        $this->assertNotNull($application, 'Proxy is null');
        $this->assertNotNull($application->getProxiedInstance(), 'Proxy\'s delegate is null');
        $this->assertEquals('TestApplication', get_class($application->getProxiedInstance()));
        $this->assertEquals('Hi World', $application->getWelcomeMessage());

        $helper = $kernel->getInstance('Helper');
        $this->assertEquals('Hello World', $helper->generateHello('World'));
    }

}
