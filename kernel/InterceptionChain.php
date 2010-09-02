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

}
