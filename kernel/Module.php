<?php
/**
 * Defines the bindings.
 *
 * @author Tobias Sarnowski
 */
interface Module {

    /**
     * Will be called to configure a module.
     *
     * @abstract
     * @param  $binder Binder the binder to use
     * @return void
     */
    function configure($binder);

}
