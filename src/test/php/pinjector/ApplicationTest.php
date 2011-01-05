<?php
/*
 * Copyright 2010,2011 Tobias Sarnowski
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
        $this->assertNotNull($application, 'application is null');
        $this->assertType('Weaved__TestApplication', $application, "not a weaved object");
        $this->assertType('TestApplication', $application, "not castable to the implementation object");
        $this->assertType('Application', $application, "not castable to the defining object");
        $this->assertnotNull($application->getHelper(), "Helper not correctly injected");
        $this->assertEquals('Hi World', $application->getWelcomeMessage(), "not the expected message");

        $helper = $kernel->getInstance('Helper');
        $this->assertNotNull($helper, 'helper is null');
        $this->assertFalse($application->getHelper() == $helper, "helper is the same reference as in application");

        $helper2 = $kernel->getInstance('Helper', 'alternative2');
        $this->assertNotNull($helper2, 'helper2 is null');

        try {
            $this->assertEquals('Hello World', $helper->generateHello('World'), "wrong message");
            $this->assertTrue(false, "previous method call should throw an exception");
        } catch (TestException $e) {
            $this->assertEquals("@break found", $e->getMessage(), "wrong exception flown");
        }

        try {
            $application->breakUs();
            $this->assertTrue(false, "previous method call should throw an exception");
        } catch (TestException $e) {
            $this->assertEquals("@break found", $e->getMessage(), "wrong exception flown");
        }
    }

}
