<?php
/*
 * Copyright 2010 Tobias Sarnowski
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
        $this->assertNotNull($application, 'Proxy is null');
        $this->assertNotNull($application->getProxiedInstance(), 'Proxy\'s delegate is null');
        $this->assertEquals('TestApplication', get_class($application->getProxiedInstance()));
        $this->assertEquals('Hi World', $application->getWelcomeMessage());

        $helper = $kernel->getInstance('Helper');
        $this->assertEquals('Hello World', $helper->generateHello('World'));
    }

}
