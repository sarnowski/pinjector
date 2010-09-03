<?php
require_once('InterceptionChain.php');

/**
 * 
 * @author Tobias Sarnowski
 */
interface Interceptor {

    /**
     * Will be called in case of an interception.
     *
     * @abstract
     * @param ReflectionMethod $method
     * @param array $params
     * @param InterceptionChain $chain
     * @return mixed
     */
    public function intercept(ReflectionMethod &$method, $params, InterceptionChain $chain);

}
