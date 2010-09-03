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
     * @param  Binder $binder the binder to use
     * @return void
     */
    public function configure(Binder $binder);

}
