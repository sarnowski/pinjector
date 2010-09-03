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
     * @param  $className
     * @return DefaultBinding
     */
    public function getBinding($className) {
        return $this->bindings[$className];
    }

    public function install(Module $module) {
        $module->configure($this);
    }

    public function bind($className) {
        if (isset($this->bindings[$className])) {
            throw new Exception("target $className already bound");
        }
        $binding = new DefaultBinding($className);
        $this->bindings[$className] = $binding;
        return $binding;
    }
}
