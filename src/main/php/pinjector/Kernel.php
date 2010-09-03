<?php
/**
 * 
 * @author Tobias Sarnowski
 */
interface Kernel {

    /**
     * Returns an instance of the given classname.
     *
     * @abstract
     * @param  string $className the classname
     * @param  string $annotation the annotation to use
     * @return mixed the requested instance
     */
    public function getInstance($className, $annotation = null);

}
