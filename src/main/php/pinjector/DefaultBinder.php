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
require_once('Binding.php');
require_once('DefaultBinding.php');
require_once('DefaultInterception.php');
require_once('DefaultRegistration.php');
require_once('Module.php');
require_once('Registry.php');

/**
 *
 * @author Tobias Sarnowski
 */
class DefaultBinder implements Binder {

    /**
     * @var array
     */
    private $registrations;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var array
     */
    private $bindings;

    /**
     * @var array
     */
    private $interceptions;

    /**
     * @private
     * @param Registry $registry
     */
    function __construct(Registry $registry) {
        $this->bindings = array();
        $this->interceptions = array();
        $this->registrations = array();
        $this->registry = $registry;
    }

    /**
     * @param  string $className
     * @param  string $annotation
     * @return DefaultBinding
     */
    public function getBinding($className, $annotation = null) {
        if (isset($this->bindings[$className])) {
            foreach ($this->bindings[$className] as $binding) {
                if ($binding->getTargetClassName() == $className && $binding->getTargetAnnotation() == $annotation) {
                    return $binding;
                }
            }
        }
        return null;
    }

    public function install(Module $module) {
        if ($module == null) {
            throw new ConfigurationException("module is null");
        }
        $module->configure($this);

        foreach ($this->registrations as $registration) {
            if ($registration->getInstance() == null) {
                $this->registry->registerBinding(
                    $registration->getKey(),
                    $registration->getClassName(),
                    $registration->getAnnotation()
                );
            } else {
                $this->registry->register(
                    $registration->getKey(),
                    $registration->getInstance()
                );
            }
        }
        $this->registrations = array();
    }

    public function bind($className) {
        $binding = new DefaultBinding($className);
        if (!isset($this->bindings[$binding->getTargetClassName()])) {
            $this->bindings[$binding->getTargetClassName()] = array();
        }
        $this->bindings[$binding->getTargetClassName()][] = $binding;
        return $binding;
    }

    public function interceptWith($className, $annotation = null) {
        $interception = new DefaultInterception($className, $annotation);
        $this->interceptions[] = $interception;
        return $interception;
    }

    public function register($registryKey) {
        $registration = new DefaultRegistration($registryKey);
        $this->registrations[] = $registration;
        return $registration;
    }

    /**
     * @return array
     */
    public function getInterceptions() {
        return $this->interceptions;
    }

    public function __toString() {
        return '{DefaultBinder bindings: '.$this->bindings.'  interceptions: '.$this->interceptions.'}';
    }
}
