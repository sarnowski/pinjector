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

require_once('Pointcut.php');


/**
 * Describes an interception configuration.
 *
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */ 
interface Interception {

    /**
     * Registers a pointcut for an interception
     *
     * @abstract
     * @param Pointcut $pointcut the pointcut to register
     * @return Interception
     */
    public function on(Pointcut $pointcut);

}
