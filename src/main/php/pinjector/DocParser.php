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
 * A utility to parse PHP comments for annotations.
 *
 * @package pinjector
 * @author Tobias Sarnowski
 * @since 1.0
 */ 
class DocParser {

    /**
     * Searches for $key in $comment with the following scheme:
     *
     *   /**
     *    * @$key
     *    *
     *
     *  Returns the trailing strings or null if not found.
     *
     * @param string $comment
     * @param string $key
     * @return array
     */
    public static function parseSettings($comment, $key) {
        if (empty($comment)) {
            return array();
        }

        $defs = array();

        $lines = explode("\n", $comment);
        foreach ($lines as $line) {
            $line = trim($line)." ";
            $match = "* @$key ";
            if (substr($line, 0, strlen($match)) == $match) {
                $defs[] = trim(substr($line, strlen($match)));
            }
        }

        return $defs;
    }

    /**
     * Same as parseSettings just returning one line or null
     * if no keyline was found.
     *
     * @param string $comment
     * @param string $key
     * @return string
     */
    public static function parseSetting($comment, $key) {
        $list = self::parseSettings($comment, $key);
        if (empty($list)) {
            return null;
        } else {
            return $list[0];
        }
    }
}
