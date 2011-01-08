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

require_once('Kernel.php');
require_once('Registry.php');
require_once('DefaultSilentRegistryCallback.php');


/**
 * Default implementation of a {@link Registry}.
 *
 * @access private
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */ 
class DefaultRegistry implements Registry {

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * @var array
     */
    private $objects = array();

    /**
     * @var array
     */
    private $bindings = array();

    function __construct(Kernel $kernel) {
        $this->kernel = $kernel;
    }

    public function call($key, RegistryCallback $callback) {
        if (isset($this->objects[$key])) {
            foreach ($this->objects[$key] as $object) {
                if ($callback->process($object) === false) {
                    return false;
                }
            }
        }
        if (isset($this->bindings[$key])) {
            foreach ($this->bindings[$key] as $binding) {
                $object = $this->kernel->getInstance($binding['className'], $binding['annotation']);
                if ($callback->process($object) === false) {
                    return false;
                }
            }
        }
        return true;
    }

    public function callSilent($key, RegistryCallback $callback) {
        return $this->call($key, new DefaultSilentRegistryCallback($callback));
    }

    public function get($key) {
        $objects = array();
        if (isset($this->objects[$key])) {
            foreach ($this->objects[$key] as $object) {
                $objects[] = $object;
            }
        }
        if (isset($this->bindings[$key])) {
            foreach ($this->bindings[$key] as $binding) {
                $objects[] = $this->kernel->getInstance($binding['className'], $binding['annotation']);
            }
        }
        return $objects;
    }

    public function register($key, $instance) {
        if (!isset($this->objects[$key])) {
            $this->objects[$key] = array();
        }
        $this->objects[$key][] = $instance;
    }

    public function registerBinding($key, $binding, $annotation = null) {
        if (!isset($this->bindings[$key])) {
            $this->bindings[$key] = array();
        }
        $this->bindings[$key][] = array('className' => $binding, 'annotation' => $annotation);
    }

    public function unregister($key, $instance) {
        if (isset($this->objects[$key])) {
            foreach ($this->objects[$key] as $k => $v) {
                if ($v == $instance) {
                    unset($this->objects[$key][$k]);
                    return;
                }
            }
        }
    }

    public function unregisterBinding($key, $binding, $annotation = null) {
        if (isset($this->bindings[$key])) {
            foreach ($this->bindings[$key] as $k => $v) {
                if ($v['className'] == $binding && $v['annotation'] == $annotation) {
                    unset($this->bindings[$key][$k]);
                    return;
                }
            }
        }
    }

}
