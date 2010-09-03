<?php
/**
 * 
 * @author Tobias Sarnowski
 */
interface Binding {

    /**
     * Defines the implementation class.
     *
     * @abstract
     * @param string $className
     * @return Binding
     */
    public function to($className);

    /**
     * Gives an already instantiated implementation.
     *
     * @abstract
     * @param  $ref
     * @return void
     */
    public function toInstance($ref);

    /**
     * Adds an annotation to the binding.
     *
     * @abstract
     * @param  $annotation
     * @return Binding
     */
    public function annotatedWith($annotation);

    /**
     * Defines the scope of the binding, requires a Scope instance.
     *
     * @abstract
     * @param  Scope $scope
     * @return void
     */
    public function in($scope);

    /**
     * Shortcut for binding in no scope.
     *
     * @abstract
     * @return void
     */
    public function inNoScope();

    /**
     * Shortcut for binding in request scope.
     *
     * @abstract
     * @return void
     */
    public function inRequestScope();

    /**
     * Shortcut for binding in session scope.
     *
     * @abstract
     * @return void
     */
    public function inSessionScope();

}
