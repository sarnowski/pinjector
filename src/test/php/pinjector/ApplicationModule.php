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

require_once('pinjector/Binder.php');
require_once('pinjector/Module.php');
require_once('Application.php');
require_once('Helper.php');
require_once('TestApplication.php');
require_once('TestHelper.php');
require_once('TestInterceptor.php');
require_once('TestPointcut.php');

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
        $binder->interceptWith('TestInterceptor')->on(new TestPointcut());
    }
}
