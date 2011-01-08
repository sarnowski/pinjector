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

require_once('InterceptionChain.php');

/**
 * Provides the {@link InterceptionChain}.
 *
 * @access private
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */ 
class DefaultInterceptionChain implements InterceptionChain {

    /**
     * @var array
     */
    private $interceptors;

    /**
     * @var mixed
     */
    private $delegate;

    /**
     * @var ReflectionMethod
     */
    private $method;

    /**
     * @var array
     */
    private $params;

    /**
     * @var int
     */
    private $index;

    function __construct($interceptors, $delegate, ReflectionMethod $method, $params) {
        $this->interceptors = $interceptors;
        $this->delegate = $delegate;
        $this->method = $method;
        $this->params = $params;
        $this->index = 0;
    }

    public function proceed() {
        if (isset($this->interceptors[$this->index])) {
            // another interceptor to call
            $interceptor = $this->interceptors[$this->index];
            $this->index++;
            return $interceptor->intercept($this);
        } else {
            // it's finished
            return $this->method->invokeArgs($this->delegate, $this->params);
        }
    }

    public function getDelegate() {
        return $this->delegate;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getParameters() {
        return $this->params;
    }

    public function setMethod(&$method) {
        $this->method = $method;
    }

    public function setParameters(&$parameters) {
        $this->params = $parameters;
    }
}
