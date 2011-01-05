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

require_once('Binder.php');
require_once('Module.php');


/**
 *
 * @author Tobias Sarnowski
 */ 
abstract class AbstractModule implements Module, Binder {

    /**
     * @var Binder
     */
    private $binder;

    public function configure(Binder $binder) {
        $this->binder = $binder;
        $this->configuration();
    }

    public abstract function configuration();

    /**
     * @return Binder
     */
    public function binder() {
        return $this->binder;
    }

    public function bind($className) {
        return $this->binder->bind($className);
    }

    public function install(Module $module) {
        $this->binder->install($module);
    }

    public function interceptWith($className, $annotation = null) {
        return $this->binder->interceptWith($className, $annotation);
    }

    public function register($registryKey) {
        return $this->binder->register($registryKey);
    }
}
