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
     * @param  $className string the classname
     * @return mixed the requested instance
     */
    public function getInstance($className);

}
