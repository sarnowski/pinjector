<?php
require_once('DefaultInterceptionChain.php');
require_once('InterceptionChain.php');

/**
 * 
 * @author Tobias Sarnowski
 */
class DefaultProxy {

    /**
     * @var mixed
     */
    private $delegate;

    /**
     * @var ReflectionClass
     */
    private $class;

    /**
     * @var DefaultKernel
     */
    private $kernel;

    /**
     * @var array
     */
    private $interceptors;

    function __construct($delegate, DefaultKernel $kernel) {
        if (is_null($delegate)) {
            throw new KernelException("proxy without delegate created");
        }

        $this->delegate = $delegate;
        $this->kernel = $kernel;

        $this->class = new ReflectionClass(get_class($delegate));

        $this->interceptors = $kernel->getBinder()->getInterceptors();
    }

    public function __call($methodName, $params) {
        $method = $this->class->getMethod($methodName);

        $interceptors = array();
        foreach ($this->interceptors as $interceptor) {
            if (isset($interceptor['instance'])) {
                $interceptors[] = $interceptor['instance'];
            } else {
                $interceptors[] = $this->kernel->getInstance($interceptor['class'], $interceptor['annotation']);
            }
        }
        $interceptors[] = $this;

        // now we have all interceptors, start the chain
        $chain = new DefaultInterceptionChain($interceptors, $this->delegate, $method, $params);
        return $chain->proceed();
    }

    public function getProxiedInstance() {
        return $this->delegate;
    }

    public function __toString() {
        return '{DefaultProxy: delegate='.$this->delegate.'}';
    }
}
