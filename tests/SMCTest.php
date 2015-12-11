<?php

/**
 *
 *
 * @author Sam Schmidt <samuel@dersam.net>
 * @since 2015-12-11
 * @company Linus Shops
 */
class SMCTest extends PHPUnit_Framework_TestCase
{
    private function copyBaseClass()
    {
        copy(
            BASE_DIR.'/resources/definitions/ExampleClass.php',
            BASE_DIR.'/resources/tmp/ExampleClass.php'
        );
    }

    private function overwriteBaseClass()
    {
        copy(
            BASE_DIR.'/resources/definitions/ModifiedExampleClass.php',
            BASE_DIR.'/resources/tmp/ExampleClass.php'
        );
    }

    private function loadBaseClass()
    {
        $this->copyBaseClass();
        include BASE_DIR.'/resources/tmp/ExampleClass.php';
    }

    public function testReloadClass()
    {
        $this->loadBaseClass();
        $class = new ExampleClass();
        $this->assertEquals('exampleclass', $class->example());
        $this->overwriteBaseClass();
        $res = SMC::reload_class('ExampleClass');
        $this->assertTrue($res);
        $this->assertEquals('changed', $class->example());
    }

    public function testReloadMethodNoArgs()
    {

    }

    public function testReloadMethodWithArgs()
    {

    }

    public function testReloadMethodWithArgAdded()
    {

    }
}
