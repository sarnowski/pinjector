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

require_once('DefaultInterceptionChain.php');
require_once('Interceptor.php');
require_once('Pointcut.php');
require_once('Weaver.php');
require_once('WeavingException.php');

/**
 * A AOP weaver implementation.
 *
 * @access private
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */ 
class DefaultWeaver implements Weaver {

    /**
     * Path to store weaved code.
     *
     * @var string
     */
    private static $weavedPath = null;

    /**
     * If the weaver allows private functions (which will be ignored).
     *
     * @var bool
     */
    private static $allowPrivate = false;

    /**
     * Path to store weaved code.
     *
     * @static
     * @param  string $weavedPath
     * @return void
     */
    public static function setWeavedPath($weavedPath) {
        self::$weavedPath = $weavedPath;
    }

    /**
     * If the weaver allows private functions (which will be ignored).
     *
     * @static
     * @param  bool $allowPrivate
     * @return void
     */
    public static function setAllowPrivate($allowPrivate) {
        self::$allowPrivate = $allowPrivate;
    }

    /**
     * @var DefaultBinder
     */
    private $binder;

    /**
     * @param DefaultBinder $binder
     * @return void
     */
    function __construct(DefaultBinder $binder) {
        $this->binder = $binder;
    }

    /**
     * Weaves a given class definition.
     *
     * @param  string $className the class name to weave
     * @return string the weaved class name
     * @throws WeavingException if something goes wrong
     */
    public function weave($className) {
        // this will be the name for the weaved class
        $weavedClassName = 'Weaved__'.$className;

        // already loaded? exit here
        if (class_exists($weavedClassName)) {
            return $weavedClassName;
        }

        // the definition to use
        $super = new ReflectionClass($className);

        // TODO check for generated weaved class file
        if (self::$weavedPath != null) {
            $originalClassFile = $super->getFileName();

            $hash = md5($originalClassFile.$className);
            $weavedClassFile = self::$weavedPath.'/'.$hash.'.php';

            if (file_exists($weavedClassFile)) {
                // do not check for modification time as we cannot be sure
                // that the inheritance path of the class is the same as before
                include($weavedClassFile);
                return $weavedClassName;
            }
        } else {
            $weavedClassFile = false;
        }

        if (!$super) {
            throw new WeavingException("$className definition not found");
        }

        // check for valid class definition
        if ($super->isFinal()) {
            throw new WeavingException("$className is final and cannot be weaved");
        }
        if ($super->isInterface()) {
            throw new WeavingException("$className is an interface and cannot be weaved");
        }
        if ($super->isAbstract()) {
            throw new WeavingException("$className is abstract and cannot be weaved");
        }

        // class header
        $class = "// Weaved class of $className\n\n\n";
        if ($super->isAbstract()) {
            $class .= "abstract ";
        }
        $class .= "class $weavedClassName extends $className {\n\n";

        // provide super class reflection
        $class .= "    private static \$superClass = null;\n\n";

        $class .= "    public static function superClass() {\n";
        $class .= "        if (self::\$superClass == null) {\n";
        $class .= "            self::\$superClass = new ReflectionClass('$className');\n";
        $class .= "        }\n";
        $class .= "        return self::\$superClass;\n";
        $class .= "    }\n\n";

        // kernel reference
        $class .= "    private \$kernel;\n\n";

        // check all methods
        $privateMethods = array();
        foreach ($super->getMethods() as $method) {

            // ignore constructor methods
            if ($method->isConstructor()) {
                continue;
            }

            // we also do not weave static methods
            if ($method->isStatic()) {
                continue;
            }

            // and we cannot weave final methods
            if ($method->isFinal()) {
                throw new WeavingException("$className->".$method->getName()."() is final and cannot be weaved");
            }

            // check for private functions which we cannot handle
            if ($method->isPrivate()) {
                if (self::$allowPrivate) {
                    continue;
                } else {
                    throw new WeavingException("$className->".$method->getName()."() is private and cannot be weaved");
                }
            }

            // check the pointcuts if someone wanted to be here
            $interceptions = array();
            foreach ($this->binder->getInterceptions() as $interception) {
                foreach ($interception->getPointcuts() as $pointcut) {
                    if ($pointcut->matchesMethod($method)) {
                        $interceptions[] = $interception;
                        break;
                    }
                }
            }

            // nobody wants to hook in?
            if (count($interceptions) == 0) {
                continue;
            }

            // generate method
            $class .= '    ';

            // add modifiers
            if ($method->isPublic()) {
                $class .= 'public ';
            } else if ($method->isProtected()) {
                $class .= 'protected ';
            }

            // which method?
            $class .= "function ".$method->getName();

            // add arguments
            $class .= "(";
            $first = true;
            $parameters = array();
            foreach ($method->getParameters() as $parameter) {
                if (!$first) {
                    $class .= ', ';
                } else {
                    $first = false;
                }

                if ($parameter->isPassedByReference()) {
                    $class .= '&';
                }

                $class .= '$'.$parameter->getName();

                if ($parameter->isDefaultValueAvailable()) {
                    $defaultValue = $parameter->getDefaultValue();
                    if (is_string($defaultValue)) {
                        $class .= " = '$defaultValue'";
                    } else {
                        $class .= " = $defaultValue";
                    }
                }

                $parameters[] = $parameter->getName();
            }
            $class .= ") {\n";

            // interceptor chaining logic
            // collect parameters
            $class .= '        $parameters = array(';
            foreach ($parameters as $parameter) {
                $class .= '$'.$parameter.',';
            }
            $class .= ");\n";

            // get super method
            $class .= "        \$superMethod = self::superClass()->getMethod('".$method->getName()."');\n";

            // collect interceptors
            $class .= "        \$interceptors = array(\n";
            foreach ($interceptions as $interception) {
                if ($interception->getAnnotation() == null) {
                    $class .= "                \$this->kernel->getInstance('".$interception->getClass()."'),\n";
                } else {
                    $class .= "                \$this->kernel->getInstance('".$interception->getClass()."', '".$interception->getAnnotation()."'),\n";
                }
            }
            $class .= "        );\n";

            // create the chain
            $class .= "        \$chain = new DefaultInterceptionChain(\$interceptors, \$this, \$superMethod, \$parameters);\n";

            // and start it
            $class .= "        return \$chain->proceed();\n";

            // close method
            $class .= "    }\n\n";
        }

        // add constructor
        $class .= "    function __construct(\$kernel, \$parameters) {\n";
        $class .= "        \$this->kernel = \$kernel;\n";
        $class .= "        \$constructor = self::superClass()->getConstructor();\n";
        $class .= "        if (!\$constructor) return;\n";
        $class .= "        if (empty(\$parameters)) {\n";
        $class .= "            \$constructor->invoke(\$this);\n";
        $class .= "        } else {\n";
        $class .= "            \$constructor->invokeArgs(\$this, \$parameters);\n";
        $class .= "        }\n";
        $class .= "    }\n";

        // class bottom
        $class .= "}\n";

        // insert the definition to the system
        if (eval($class) === FALSE) {
            throw new WeavingException("Generated weaved class is incorrect; internal problem!");
        }

        // store class definition to file
        if ($weavedClassFile) {
            if (is_writable(self::$weavedPath)) {
                if (file_put_contents($weavedClassFile, "<?php\n".$class) === FALSE) {
                    throw new WeavingException("Cannot write to file ".$weavedClassFile);
                }
            } else {
                throw new WeavingException("Cannot write to directory ".self::$weavedPath);
            }
        }

        // we're done
        return $weavedClassName;
    }

}
