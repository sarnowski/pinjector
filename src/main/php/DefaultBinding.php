<?php
require_once('Binding.php');
require_once('ConfigurationException.php');

/**
 * 
 * @author Tobias Sarnowski
 */
class DefaultBinding implements Binding {

    /**
     * @var string
     */
    private $targetClassName;

    /**
     * @var string
     */
    private $sourceClassName;

    /**
     * @var mixed
     */
    private $sourceInstance;


    function __construct($className) {
        $this->sourceClassName = null;
        $this->sourceInstance = null;

        $this->targetClassName = $className;
    }

    private function checkAlreadySet() {
        if (!is_null($this->sourceClassName) || !is_null($this->sourceInstance)) {
            throw new ConfigurationException("binding already configured to another source");
        }
    }

    public function to($className) {
        $this->checkAlreadySet();
        $this->sourceClassName = $className;
        return $this;
    }

    public function toInstance($ref) {
        $this->checkAlreadySet();
        $this->sourceInstance = $ref;
        return $this;
    }

    /**
     * @return string
     */
    public function getSourceClassName() {
        return $this->sourceClassName;
    }

    /**
     * @return mixed
     */
    public function getSourceInstance() {
        return $this->sourceInstance;
    }

    /**
     * @return string
     */
    public function getTargetClassName() {
        return $this->targetClassName;
    }

}
