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

        // if an instance exists, give it out, we can not do more
        $instance = $binding->getSourceInstance();
        if (!is_null($instance)) {
            return new DefaultProxy($instance, $this);
        }

        // ok, let's do our magic
        $sourceClass = $binding->getSourceClassName();
        if (is_null($sourceClass)) {
            $sourceClass = $binding->getTargetClassName();
        }

        // do the injection magic
        $class = new ReflectionClass($sourceClass);


        // TODO


        // instantiate!
        $instance = $class->newInstanceArgs();

        // wrap with a proxy
        return new DefaultProxy($instance, $this);
    }
}
