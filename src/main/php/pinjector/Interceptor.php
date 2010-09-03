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
     * @param InterceptionChain $chain
     * @return mixed
     */
    public function intercept(InterceptionChain $chain);

}
