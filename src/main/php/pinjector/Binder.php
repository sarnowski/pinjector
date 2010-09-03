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

require_once('Binder.php');
require_once('Module.php');

/**
 * Utility to define bindings.
 *
 * @author Tobias Sarnowski
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
     * @param  string $className
     * @return Binding
     */
    public function bind($className);


    /**
     * Register an interception handler.
     *
     * @abstract
     * @param  string $className
     * @param  string $annotation
     * @return void
     */
    public function interceptWith($className, $annotation = null);

    /**
     * Register an interception handler.
     *
     * @abstract
     * @param  mixed $instance
     * @return void
     */
    public function interceptWithInstance($instance);

}
