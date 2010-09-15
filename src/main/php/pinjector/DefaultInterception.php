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

require_once('Interception.php');


/**
 *
 * @author Tobias Sarnowski
 */
class DefaultInterception implements Interception {

    private $class;

    private $annotation;

    private $pointcuts = array();

    function __construct($class, $annotation) {
        $this->class = $class;
        $this->annotation = $annotation;
    }

    public function on(Pointcut $pointcut) {
        $this->pointcuts[] = $pointcut;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @return string
     */
    public function getAnnotation() {
        return $this->annotation;
    }

    /**
     * @return array
     */
    public function getPointcuts() {
        return $this->pointcuts;
    }

    public function __toString() {
        return '{DefaultInterception class='.$this->class.' pointcuts='.count($this->pointcuts).'}';
    }
}
