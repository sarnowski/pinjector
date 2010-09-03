<?php
require_once('pinjector/AbstractInterceptor.php');
require_once('Helper.php');

/**
 *
 * @author Tobias Sarnowski
 */ 
class TestInterceptor extends AbstractInterceptor {

    /**
     * @var Helper
     */
    private $helper;

    /**
     * @param  Helper $helper
     * @return void
     */
    function __construct($helper) {
        $this->helper = $helper;
    }


    /**
     * Throws an exception if a method is annotated with @break
     *
     * @throws Exception
     * @param ReflectionMethod $method
     * @param  $params
     * @param InterceptionChain $chain
     * @return
     */
    public function intercept(ReflectionMethod &$method, $params, InterceptionChain $chain) {
        // throws an exception if it finds @break
        $value = $this->parseSetting($method->getDocComment(), 'break');

        if (!is_null($value)) {
            $message = $this->helper->generateHello("Interceptor");
            throw new Exception("Comment triggered exception: ".$message);

        } else {
            return $chain->proceed();
        }
    }

    public function __toString() {
        return '{TestInterceptor}';
    }
}
