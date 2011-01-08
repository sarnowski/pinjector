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
 * $obj can be an object or a class name and if given, will be checked
 * if it is castable to the $className.
 *
 * @param mixed $obj object to check
 * @param string $className class to check against
 * @return bool
 */
function is_class($obj, $className = null) {
    if (is_null($obj)) return false;
    if (!is_string($obj)) return is_class(get_class($obj));
    if (!class_exists($obj) && !interface_exists($obj)) return false;
    if (!is_null($className)) return is_castable($className, $obj);
    return true;
}

/**
 * Checks if one class is castable to another.
 * 
 * @param string $targetClass cast target
 * @param string $sourceClass cast source
 * @return bool
 */
function is_castable($targetClass, $sourceClass) {
    if ($targetClass == $sourceClass) return true;
    $classes = class_parents($sourceClass);
    if (in_array($targetClass, $classes)) return true;
    $interfaces = class_implements($sourceClass);
    if (in_array($targetClass, $interfaces)) return true;
    return false;
}
