<?php
require_once('InterceptionChain.php');

/**
 *
 * @author Tobias Sarnowski
 */ 
class DefaultInterceptionChain implements InterceptionChain {

    /**
     * @var array
     */
    private $interceptors;

    /**
     * @var mixed
     */
    private $delegate;

    /**
     * @var ReflectionMethod
     */
    private $method;

    /**
     * @var array
     */
    private $params;

    /**
     * @var int
     */
    private $index;

    function __construct($interceptors, $delegate, ReflectionMethod $method, $params) {
        $this->interceptors = $interceptors;
        $this->delegate = $delegate;
        $this->method = $method;
        $this->params = $params;
        $this->index = 0;
    }

    public function proceed() {
        $interceptor = $this->interceptors[$this->index];
        $this->index++;
        if ($this->index <= count($interceptor)) {
            return $interceptor->getProxiedInstance()->intercept($this->method, $this->params, $this);
        } else {
            // it's finished
            return $this->method->invokeArgs($this->delegate, $this->params);
        }
    }
}
