<?php
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

    public function __construct($delegate, DefaultKernel $kernel) {
        $this->delegate = $delegate;
        $this->kernel = $kernel;

        $this->class = new ReflectionClass(get_class($delegate));
    }

    public function __call($methodName, $params) {

        // TODO let interceptors do their work..

        $method = $this->class->getMethod($methodName);
        return $method->invokeArgs($this->delegate, $params);
    }

}
