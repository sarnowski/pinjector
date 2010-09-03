<?php
require_once('Binding.php');
require_once('ConfigurationException.php');
require_once('DefaultRequestScope.php');
require_once('DefaultSessionScope.php');

/**
 * 
 * @author Tobias Sarnowski
 */
class DefaultBinding implements Binding {

    private static $requestScope;
    private static $sessionScope;

    static function initializeScopes() {
        self::$requestScope = new DefaultRequestScope();
        self::$sessionScope = new DefaultSessionScope();
    }

    /**
     * @var string
     */
    private $targetClassName;

    /**
     * @var string
     */
    private $targetAnnotation;

    /**
     * @var string
     */
    private $sourceClassName;

    /**
     * @var mixed
     */
    private $sourceInstance;

    /**
     * @var Scope
     */
    private $scope;


    function __construct($className) {
        $this->targetAnnotation = null;
        $this->sourceClassName = null;
        $this->sourceInstance = null;
        $this->scope = null;

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

    /**
     * @return string
     */
    public function getTargetAnnotation() {
        return $this->targetAnnotation;
    }

    public function annotatedWith($annotation) {
        if (!is_null($this->targetAnnotation)) {
            throw new ConfigurationException("annotation already set");
        }
        $this->targetAnnotation = $annotation;
        return $this;
    }

    public function in($scope) {
        $this->scope = $scope;
    }

    public function inNoScope() {
        $this->scope = null;
    }

    public function inRequestScope() {
        $this->scope = self::$requestScope;
    }

    public function inSessionScope() {
        $this->scope = self::$sessionScope;
    }

    public function getScope() {
        return $this->scope;
    }

    public function getKey() {
        if ($this->targetAnnotation == null) {
            return $this->targetClassName;
        } else {
            return $this->targetClassName.",".$this->targetAnnotation;
        }
    }

    public function __toString() {
        return '{DefaultBinding'
                .' targetClassName='.$this->targetClassName
                .' targetAnnotation='.$this->targetAnnotation
                .' '
                .'}';
    }
}

// hack: initialize static content
DefaultBinding::initializeScopes();