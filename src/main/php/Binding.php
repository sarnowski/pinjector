<?php
/**
 * 
 * @author Tobias Sarnowski
 */
interface Binding {

    /**
     * @abstract
     * @param string $className
     * @return Binding
     */
    public function to($className);

    /**
     * @abstract
     * @param  $ref
     * @return Binding
     */
    public function toInstance($ref);

}
