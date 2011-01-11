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
require_once('DefaultBinder.php');
require_once('DefaultBinding.php');
require_once('DefaultNoScope.php');
require_once('DefaultRequestScope.php');
require_once('DefaultRegistry.php');
require_once('DefaultWeaver.php');
require_once('DocParser.php');
require_once('Kernel.php');
require_once('KernelException.php');
require_once('Module.php');
require_once('utilities.php');

/**
 * Default kernel implementation. Provides a factory method
 * to bootstrap a {@link Kernel}.
 *
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */
class DefaultKernel implements Kernel {

    /**
     * Boots the kernel with the given {@link Module}.
     *
     * @static
     * @param Module $applicationModule the initial module to use for configuration
     * @return Kernel the booted kernel
     */
    public static function boot($applicationModule = null) {
        $kernel = new DefaultKernel();
        // bind the kernel itself
        $kernel->getBinder()->bind('Kernel')->toInstance($kernel);
        // load the application module
	if (!is_null($applicationModule)) {
            $kernel->install($applicationModule);
        }
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
     * @var DefaultRegistry
     */
    private $registry;

    /**
     * @access private
     * @private
     */
    function __construct() {
        $this->registry = new DefaultRegistry($this);
        $this->binder = new DefaultBinder($this->registry);
        $this->weaver = new DefaultWeaver($this->binder);

        // bind to default scopes (they will hardly get other implementations)
        $this->binder->bindScope('NoScope')->toInstance(new DefaultNoScope());
        $this->binder->bindScope('RequestScope')->toInstance(new DefaultRequestScope());

        $this->binder->bind('Registry')->toInstance($this->registry);
        $this->binder->bind('Weaver')->toInstance($this->weaver);
    }

    /**
     * @access private
     * @return DefaultBinder the used binder
     */
    public function getBinder() {
        return $this->binder;
    }

    /**
     * @access private
     */
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

        // instance bound? directly return
        if (!is_null($binding->getSourceInstance())) {
            return $binding->getSourceInstance();
        }

        // get the scope of the binding
        $scope = $this->getInstance(
            $binding->getScope(),
            $binding->getScopeAnnotation());

        // get from scope and if not available scope a new instance
        $scopeKey = $binding->getKey();
        $instance = $scope->getScope($scopeKey);
        if (is_null($instance)) {
            $instance = $this->newInstance($binding);
            $scope->putScope($scopeKey, $instance);
        }

        // return the proxied instance
        return $instance;
    }

    /**
     * @access private
     */
    private function newInstance(DefaultBinding $binding) {
        if (!is_null($binding->getSourceInstance())) {
            return $binding->getSourceInstance();
        }

        // which is the real implementation now?
        $sourceClass = $binding->getSourceClassName();
        $sourceAnnotation = $binding->getSourceAnnotation();

        if (is_null($sourceClass)) {
            // implementation directly bound
            $sourceClass = $binding->getTargetClassName();

        } else {
            // source is a binding itself?
            $sourceBinding = $this->binder->getBinding($sourceClass, $sourceAnnotation);
            if ($sourceBinding != null) {
                return $this->newInstance($sourceBinding);
            }

            // annotation given?
            if ($sourceAnnotation != null) {
                throw new ConfigurationException("Source annotation given but no appropriate binding found");
            }
        }

        // now we have the implementation, load it
        return $this->instantiate($sourceClass);
    }

    /**
     * @access private
     */
    private function instantiate($className, $parameters = null) {
        // get weaved class definition
        $weavedClassName = $this->weaver->weave($className);
        $weavedClass = new ReflectionClass($weavedClassName);

        // do the injection magic
        // is only php 5.3+ compatible: $class = $weavedClassName::superClass();
        // better:
        $class = $weavedClass->getMethod("superClass")->invoke(null);

        // prepare constructor injection
        if ($parameters == null) {
            $injection = true;
            $constructor = $class->getConstructor();
            $parameters = $this->getDependencies($constructor);
        } else {
            $injection = false;
        }

        // instantiate!
        $instance = $weavedClass->newInstanceArgs(array($this, $parameters));

        // do method injection
        if ($injection) {
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
        }

        return $instance;
    }

    /**
     * @access private
     */
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

    /**
     * @access private
     */
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

    /**
     * @access private
     */
    public function install(Module $module) {
        $this->binder->install($module);
    }

    /**
     * @access private
     */
    public function createInstance($className, $arguments = null) {
        if ($arguments == null) {
            $arguments = array();
        }
        return $this->instantiate($className, $arguments);
    }
}
