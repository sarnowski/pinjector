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

require_once('RegistryCallback.php');


/**
 *
 * @author Tobias Sarnowski
 */ 
interface Registry {

    /**
     * Registers an object instance to the key in the registry.
     *
     * @abstract
     * @param  mixed $key
     * @param  mixed $instance
     * @return void
     */
    public function register($key, $instance);

    /**
     * Registers a binding to the key in the registry.
     *
     * @abstract
     * @param  mixed $key
     * @param  string $binding
     * @param  string $annotation
     * @return void
     */
    public function registerBinding($key, $binding, $annotation = null);

    /**
     * Unregisters an instance from the registry.
     *
     * @abstract
     * @param  mixed $key
     * @param  mixed $instance
     * @return void
     */
    public function unregister($key, $instance);

    /**
     * Unregisters a binding from the registry.
     *
     * @abstract
     * @param  $key
     * @param  $binding
     * @param  $annotation
     * @return void
     */
    public function unregisterBinding($key, $binding, $annotation = null);

    /**
     * Returns an array of all registered objects matching the key.
     *
     * @abstract
     * @param  mixed $key
     * @return array
     */
    public function get($key);

    /**
     * Calls the callback for every found entry in the registry.
     *
     * @abstract
     * @param  mixed $key
     * @param  RegistryCallback $callback
     * @return void
     */
    public function call($key, RegistryCallback $callback);

    /**
     * Calls the callback for every found entry in the registry and
     * ignores exceptions during execution.
     *
     * @abstract
     * @param  mixed $key
     * @param  RegistryCallback $callback
     * @return void
     */
    public function callSilent($key, RegistryCallback $callback);

}