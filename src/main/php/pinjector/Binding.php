<?php
/*
 * Copyright 2010,2011 Tobias Sarnowski
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * 
 * @author Tobias Sarnowski
 */
interface Binding {

    /**
     * Defines the implementation class or another binding.
     *
     * @abstract
     * @param string $className
     * @param string $annotation
     * @return Binding
     */
    public function to($className, $annotation = null);

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
