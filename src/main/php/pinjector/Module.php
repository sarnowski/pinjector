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

require_once('Binder.php');

/**
 * Modules configure their bindings and the whole application
 * composition.
 *
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */
interface Module {

    /**
     * Will be called to configure the application.
     *
     * @abstract
     * @param Binder $binder the binder to use
     * @return void
     */
    public function configure(Binder $binder);

}
