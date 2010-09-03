<?php
require_once('Binder.php');
require_once('Binding.php');
require_once('DefaultBinding.php');
require_once('Module.php');

/**
 *
 * @author Tobias Sarnowski
 */
class DefaultBinder implements Binder {

    private $bindings;

    /**
     * @private
     */
    function __construct() {
        $this->bindings = array();
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
        $module->configure($this);
    }

    public function bind($className) {
        $binding = new DefaultBinding($className);
        if (!isset($this->bindings[$binding->getTargetClassName()])) {
            $this->bindings[$binding->getTargetClassName()] = array();
        }
        $this->bindings[$binding->getTargetClassName()][] = $binding;
        return $binding;
    }

    public function __toString() {
        return '{DefaultBinder binding: '.$this->bindings.'}';
    }
}
