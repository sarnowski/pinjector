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
 * Defines a scope implementation.
 *
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */
interface Scope {

    /**
     * Stores an instantiated binding in the scope.
     *
     * @abstract
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function putScope($key, $value);

    /**
     * Returns the stored instance or null if instance not found.
     *
     * @abstract
     * @param mixed $key
     * @return mixed
     */
    public function getScope($key);

}
