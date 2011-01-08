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
require_once('ConfigurationException.php');
require_once('Scope.php');

/**
 * Keeps track of a binding statement.
 *
 * @access private
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */
class DefaultBinding implements Binding {

    /**
     * @var string
     */
    private $targetClassName;

    /**
     * @var string
     */
    private $targetAnnotation;

    /**
     * @var string
     */
    private $sourceClassName;

    /**
     * @var string
     */
    private $sourceAnnotation;

    /**
     * @var mixed
     */
    private $sourceInstance;

    /**
     * @var Scope
     */
    private $scope;

    /**
     * @var string
     */
    private $scopeAnnotation;


    function __construct($className) {
        $this->targetAnnotation = null;
        $this->sourceClassName = null;
        $this->sourceAnnotation = null;
        $this->sourceInstance = null;
        $this->scope = 'RequestScope';
        $this->scopeAnnotation = null;

        $this->targetClassName = $className;
    }

    private function checkAlreadySet() {
        if (!is_null($this->sourceClassName) || !is_null($this->sourceInstance)) {
            throw new ConfigurationException("Binding already configured to another source");
        }
    }

    public function to($className, $annotation = null) {
        $this->checkAlreadySet();
        if (!is_class($className, $this->targetClassName)) {
            throw new ConfigurationException('Given source binding "'.$className.'" is not a subclass of the target binding "'.$this->targetClassName.'".');
        }
        $this->sourceClassName = $className;
        $this->sourceAnnotation = $annotation;
        return $this;
    }

    public function toInstance($ref) {
        $this->checkAlreadySet();
	if (!is_class($ref, $this->targetClassName)) {
            throw new ConfigurationException('Given source instance "'.get_class($ref).'" is not a subclass of the target binding "'.$this->targetClassName.'".');
        }
        $this->sourceInstance = $ref;
    }

    /**
     * @return string
     */
    public function getSourceClassName() {
        return $this->sourceClassName;
    }

    /**
     * @return string
     */
    public function getSourceAnnotation() {
        return $this->sourceAnnotation;
    }

    /**
     * @return mixed
     */
    public function getSourceInstance() {
        return $this->sourceInstance;
    }

    /**
     * @return string
     */
    public function getTargetClassName() {
        return $this->targetClassName;
    }

    /**
     * @return string
     */
    public function getTargetAnnotation() {
        return $this->targetAnnotation;
    }

    public function annotatedWith($annotation) {
        if (!is_null($this->targetAnnotation)) {
            throw new ConfigurationException("Annotation already set.");
        }
        $this->targetAnnotation = $annotation;
        return $this;
    }

    public function in($scope, $scopeAnnotation = null) {
        if (!is_class($scope, 'Scope')) {
            throw new ConfigurationException('Given scope "'.$scope.'" is not a Scope class.');
        }
        $this->scope = $scope;
        $this->scopeAnnotation = $scopeAnnotation;
    }

    public function inNoScope() {
        $this->scope = 'NoScope';
    }

    public function inRequestScope() {
        $this->scope = 'RequestScope';
    }

    public function getScope() {
        return $this->scope;
    }

    public function getScopeAnnotation() {
        return $this->scopeAnnotation;
    }

    public function getKey() {
        if ($this->targetAnnotation == null) {
            return $this->targetClassName;
        } else {
            return $this->targetClassName.",".$this->targetAnnotation;
        }
    }

    public function __toString() {
        return '{DefaultBinding'
                .' targetClassName='.$this->targetClassName
                .' targetAnnotation='.$this->targetAnnotation
                .'}';
    }
}
