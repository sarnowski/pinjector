<?php


/**
 *
 * @author Tobias Sarnowski
 */
interface Scope {

    /**
     * Stores an instantiated binding in the scope.
     *
     * @abstract
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function putScope($key, $value);

    /**
     * Returns the stored instance or null if instance not found.
     *
     * @abstract
     * @param  string $key
     * @return mixed
     */
    public function getScope($key);

}
