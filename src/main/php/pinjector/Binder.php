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

require_once('Binding.php');
require_once('Interception.php');
require_once('Module.php');
require_once('Registration.php');


/**
 * Utility to define bindings.
 *
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 * @see Module
 */
interface Binder {

    /**
     * Installs a module.
     *
     * @abstract
     * @param Module $module
     * @return void
     */
    public function install(Module $module);

    /**
     * Creates a new binding.
     *
     * @abstract
     * @param string $className
     * @return Binding
     */
    public function bind($className);


    /**
     * Register an interception handler.
     *
     * @abstract
     * @param string $className
     * @param string $annotation
     * @return Interception
     */
    public function interceptWith($className, $annotation = null);

    /**
     * Creates a registration for the given key.
     *
     * @abstract
     * @param string $registryKey
     * @return Registration
     */
    public function register($registryKey);

}
