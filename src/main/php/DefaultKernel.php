<?php
require_once('Binder.php');
require_once('Binding.php');
require_once('DefaultBinder.php');
require_once('DefaultBinding.php');
require_once('DefaultProxy.php');
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
        $kernel->getBinder()->install($applicationModule);
        return $kernel;
    }


    /**
     * @var DefaultBinder
     */
    private $binder;

    /**
     * @private
     */
    function __construct() {
        $this->binder = new DefaultBinder();
    }

    /**
     * @return Binder the used binder
     */
    private function getBinder() {
        return $this->binder;
    }

    public function getInstance($className) {
        $binding = $this->binder->getBinding($className);
        if (is_null($binding)) {
            throw new KernelException("class binding not found");
        }

        // initialize if nessecary
        if (is_null($binding->getSourceInstance())) {
            $this->initializeBinding($binding);
        }

        // return the proxied instance
        return new DefaultProxy($binding->getSourceInstance(), $this);
    }

    private function initializeBinding(DefaultBinding $binding) {
        // ok, let's do our magic
        $sourceClass = $binding->getSourceClassName();
        if (is_null($sourceClass)) {
            $sourceClass = $binding->getTargetClassName();
        }

        // do the injection magic
        $class = new ReflectionClass($sourceClass);

        // prepare constructor injection
        $constructor = $class->getConstructor();
        $parameters = array();

        if (!empty($constructor)) {
            $injectionDefs = $this->getInjectionDefinitions($constructor, '@inject');

            // resolve dependencies
            foreach ($constructor->getParameters() as $parameter) {
                $name = $parameter->getName();
                $dependency = null;

                foreach ($injectionDefs as $def) {
                    if ($def['name'] == '$'.$name) {
                        $dependency = $this->getInstance($def['class']);
                    }
                }

                if ($dependency == null) {
                    throw new KernelException("Parameter dependency '$name' not defined.");
                }
                $parameters[] = $dependency;
            }
        }

        // instantiate!
        if (empty($parameters)) {
            $instance = $class->newInstanceArgs();
        } else {
            $instance = $class->newInstanceArgs($parameters);
        }

        // save
        $binding->setSourceInstance($instance);
    }

    private function getInjectionDefinitions($method) {
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
                    );
                    // TODO use $value[2] for binding keys
                    $defs[] = $def;
                } else {
                    throw new KernelException("unknown @inject parameters");
                }
            }
        }

        return $defs;
    }
}
