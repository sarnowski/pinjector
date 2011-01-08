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

/**
 * Defines an interception chain for method interception.
 * 
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */
interface InterceptionChain {

    /**
     * Proceed with the method execution.
     *
     * @abstract
     * @return mixed
     */
    public function proceed();

    /**
     * The object to work on.
     *
     * @abstract
     * @return mixed
     */
    public function getDelegate();

    /**
     * The method to call later.
     *
     * @abstract
     * @return ReflectionMethod
     */
    public function getMethod();

    /**
     * Sets the method to call later.
     *
     * @abstract
     * @param  ReflectionMethod $method
     * @return void
     */
    public function setMethod(&$method);

    /**
     * Array of parameters.
     *
     * @abstract
     * @return array
     */
    public function getParameters();

    /**
     * Sets the array of parameters.
     *
     * @abstract
     * @param  $parameters
     * @return array
     */
    public function setParameters(&$parameters);

}
