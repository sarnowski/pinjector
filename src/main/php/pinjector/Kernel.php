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
 * Core definition for direct interaction with the pinjector library.
 * 
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */
interface Kernel {

    /**
     * Returns an instance of the given classname.
     *
     * @abstract
     * @param string $className the classname
     * @param string $annotation the annotation to use
     * @return mixed the requested instance
     */
    public function getInstance($className, $annotation = null);

    /**
     * Installs an additional module.
     *
     * @abstract
     * @param Module $module
     * @return void
     */
    public function install(Module $module);

    /**
     * Instantiates an object of the given classname with the given arguments.
     *
     * @abstract
     * @param string $className
     * @param array $arguments
     * @return mixed
     */
    public function createInstance($className, $arguments = null);

}
