<?php
require_once('Binder.php');
require_once('Module.php');

/**
 * Utility to define bindings.
 *
 * @author Tobias Sarnowski
 */
interface Binder {

    /**
     * Installs a module.
     *
     * @abstract
     * @param Module $module
     * @return void
     */
    public function install(Module $module);

    /**
     * Creates a new binding.
     *
     * @abstract
     * @param  string $className
     * @return Binding
     */
    public function bind(string $className);

}
