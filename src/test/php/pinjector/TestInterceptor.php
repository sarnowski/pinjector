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

require_once('pinjector/AbstractInterceptor.php');
require_once('Helper.php');

/**
 *
 * @author Tobias Sarnowski
 */ 
class TestInterceptor extends AbstractInterceptor {

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param  Helper $helper
     * @return void
     */
    function __construct($helper) {
        $this->helper = $helper;
    }


    /**
     * Throws an exception if a method is annotated with @break
     *
     * @throws Exception
     * @param ReflectionMethod $method
     * @param  $params
     * @param InterceptionChain $chain
     * @return
     */
    public function intercept(InterceptionChain $chain) {
        // throws an exception if it finds @break
        $value = $this->parseSetting($chain->getMethod()->getDocComment(), 'break');

        if (!is_null($value)) {
            $message = $this->helper->generateHello("Interceptor");
            throw new Exception("Comment triggered exception: ".$message);

        } else {
            return $chain->proceed();
        }
    }

    public function __toString() {
        return '{TestInterceptor}';
    }
}
