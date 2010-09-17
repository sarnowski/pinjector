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

require_once('Registration.php');


/**
 *
 * @author Tobias Sarnowski
 */ 
class DefaultRegistration implements Registration {

    /**
     * @var string
     */
    private $key = null;

    /**
     * @var mixed
     */
    private $instance = null;

    /**
     * @var string
     */
    private $className = null;

    /**
     * @var string
     */
    private $annotation = null;


    function __construct($key) {
        $this->key = $key;
    }

    public function to($className, $annotation = null) {
        $this->className = $className;
        $this->annotation = $annotation;
    }

    public function toInstance($instance) {
        $this->instance = $instance;
    }

    public function getKey() {
        return $this->key;
    }

    public function getInstance() {
        return $this->instance;
    }

    public function getClassName() {
        return $this->className;
    }

    public function getAnnotation() {
        return $this->annotation;
    }
}
