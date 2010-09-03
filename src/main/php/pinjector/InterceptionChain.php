<?php
/**
 * 
 * @author Tobias Sarnowski
 */
interface InterceptionChain {

    /**
     * Proceed with the execution.
     *
     * @abstract
     * @return mixed
     */
    public function proceed();

    /**
     * The object to work on.
     *
     * @abstract
     * @return mixed
     */
    public function getDelegate();

    /**
     * The method to call later.
     *
     * @abstract
     * @return ReflectionMethod
     */
    public function getMethod();

    /**
     * Sets the method to call later.
     *
     * @abstract
     * @param  ReflectionMethod $method
     * @return void
     */
    public function setMethod(&$method);

    /**
     * Array of parameters.
     *
     * @abstract
     * @return array
     */
    public function getParameters();

    /**
     * Sets the array of parameters.
     *
     * @abstract
     * @param  $parameters
     * @return array
     */
    public function setParameters(&$parameters);

}
