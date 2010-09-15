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
require_once('Binding.php');
require_once('DefaultBinder.php');
require_once('DefaultBinding.php');
require_once('DefaultWeaver.php');
require_once('DocParser.php');
require_once('Kernel.php');
require_once('KernelException.php');
require_once('Module.php');

/**
 * Default kernel implementation.
 *
 * @author Tobias Sarnowski
 */
class DefaultKernel implements Kernel {

    /**
     * Boots the application.
     *
     * @static
     * @param  Module $applicationModule the bootstrap object
     * @return Kernel the booted kernel
     */
    public static function boot(Module $applicationModule) {
        $kernel = new DefaultKernel();
        $kernel->getBinder()->bind('Kernel')->toInstance($kernel); // bind the kernel itself
        $kernel->getBinder()->install($applicationModule);
        return $kernel;
    }


    /**
     * @var DefaultBinder
     */
    private $binder;

    /**
     * @var DefaultWeaver
     */
    private $weaver;

    /**
     * @private
     */
    function __construct() {
        $this->binder = new DefaultBinder();
        $this->weaver = new DefaultWeaver($this->binder);
    }

    /**
     * @return DefaultBinder the used binder
     */
    public function getBinder() {
        return $this->binder;
    }

    public function getInstance($className, $annotation = null, $allowNull = false) {
        $binding = $this->binder->getBinding($className, $annotation);
        if (is_null($binding) && !$allowNull) {
            if ($annotation == null) {
                throw new ConfigurationException("class binding for $className not found");
            } else {
                throw new ConfigurationException("class binding for $className [$annotation] not found");
            }
        } elseif (is_null($binding)) {
            return null;
        }

        // initialize if nessecary
        $scope = $binding->getScope();
        if (is_null($scope)) {
            $instance = $this->newInstance($binding);
        } else {
            $scopeKey = $binding->getKey();
            $instance = $scope->getScope($scopeKey);
            if (is_null($instance)) {
                $instance = $this->newInstance($binding);
                $scope->putScope($scopeKey, $instance);
            }
        }

        // return the proxied instance
        return $instance;
    }

    private function newInstance(DefaultBinding $binding) {
        if (!is_null($binding->getSourceInstance())) {
            return $binding->getSourceInstance();
        }

        // ok, let's do our magic
        $sourceClass = $binding->getSourceClassName();
        if (is_null($sourceClass)) {
            // TODO targetClass itself already bound
            $sourceClass = $binding->getTargetClassName();
        }

        // get weaved class definition
        $weavedClassName = $this->weaver->weave($sourceClass);
        $weavedClass = new ReflectionClass($weavedClassName);

        // do the injection magic
        $class = $weavedClassName::superClass();

        // prepare constructor injection
        $constructor = $class->getConstructor();
        $parameters = $this->getDependencies($constructor);

        // instantiate!
        $instance = $weavedClass->newInstanceArgs(array($this, $parameters));

        // do method injection
        foreach ($class->getMethods() as $method) {
            // does it have an @optional?
            if (!is_null(DocParser::parseSetting($method->getDocComment(), 'optional'))) {
                $parameters = $this->getDependencies($method, true);
                $call = true;
                foreach ($parameters as $parameter) {
                    if ($parameter == null) {
                        $call = false;
                        break;
                    }
                }
                if ($call) {
                    $method->invokeArgs($instance, $parameters);
                }
            }
        }

        return $instance;
    }

    private function getDependencies(&$method, $optionals = false) {
        $parameters = array();
        if (!empty($method)) {
            $injectionDefs = $this->getInjectionDefinitions($method);

            // resolve dependencies
            foreach ($method->getParameters() as $parameter) {
                $name = $parameter->getName();
                $dependency = null;

                foreach ($injectionDefs as $def) {
                    if ($def['name'] == '$'.$name) {
                        $dependency = $this->getInstance($def['class'], $def['annotation'], $optionals);
                    }
                }

                if ($dependency == null && !$optionals) {
                    throw new ConfigurationException("Parameter dependency '$name' not defined.");
                }
                $parameters[] = $dependency;
            }
        }
        return $parameters;
    }

    private function getInjectionDefinitions(&$method) {
        $doc = $method->getDocComment();
        if (empty($doc)) {
            return array();
        }

        $defs = array();

        $lines = explode("\n", $doc);
        foreach ($lines as $line) {
            $line = trim($line);
            $match = "* @param ";
            if (substr($line, 0, strlen($match)) == $match) {
                $value = explode(' ', trim(substr($line, strlen($match))));
                if (count($value) >= 2) {
                    $def = array(
                        'class' => $value[0],
                        'name' => $value[1],
                        'annotation' => null
                    );
                    if (count($value) >= 3) {
                        if ($value[2][0] == '!') {
                            $value = trim($value[2]);
                            $def['annotation'] = substr($value, 1);
                        }
                    }
                    $defs[] = $def;
                } else {
                    throw new ConfigurationException("too few arguments on @param definition");
                }
            }
        }

        return $defs;
    }

    public function install(Module $module) {
        $this->binder->install($module);
    }
}
