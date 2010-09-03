<?php
/*
 * Copyright 2010 Tobias Sarnowski
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

require_once('Application.php');

/**
 *
 * @author Tobias Sarnowski
 */
class TestApplication implements Application {

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param Helper $helper !alternative the alternative helper implementation
     * @return void
     */
    function __construct($helper) {
        $this->helper = $helper;
    }

    /**
     * @optional
     * @param  Helper $helper
     * @return void
     */
    public function setNormalHelper($helper) {
        // will be called if a Helper was bound
    }

    /**
     * @optional
     * @param  Helper $helper !unknown
     * @return void
     */
    public function setUnknownHelper($helper) {
        // will be called if a Helper !unknown was bound
    }

    public function getWelcomeMessage() {
        return $this->helper->generateHello("World");
    }

    public function __toString() {
        return '{TestApplication}';
    }
}
