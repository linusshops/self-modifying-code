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
        $this->loadBaseClass();
        $class = new ExampleClass();
        $this->assertEquals('exampleclass', $class->example());
        $this->overwriteBaseClass();

        $res = SMC::reload_method('ExampleClass', 'example');
        $this->assertTrue($res);

        $this->assertEquals('changed', $class->example());

        //Reload it again, as there are potential issues
        //where runkit clears out the source filename, this makes sure
        //they are handled properly
        $res = SMC::reload_method('ExampleClass', 'example');
        $this->assertTrue($res);

        $this->assertEquals('changed', $class->example());
    }

    public function testReloadMethodWithArgs()
    {
        $this->loadBaseClass();
        $class = new ExampleClass();
        $this->assertEquals('original', $class->exampleArg(null));
        $this->overwriteBaseClass();

        $res = SMC::reload_method('ExampleClass', 'exampleArg');
        $this->assertTrue($res);

        $this->assertEquals('changedargmeth', $class->exampleArg('changedargmeth'));
    }

    public function testReloadMethodWithArgDefaults()
    {
        $this->loadBaseClass();
        $class = new ExampleClass();
        $this->assertEquals('original', $class->exampleArgDefault());
        $this->overwriteBaseClass();

        $res = SMC::reload_method('ExampleClass', 'exampleArgDefault');
        $this->assertTrue($res);

        $this->assertEquals('changedargmeth', $class->exampleArgDefault('changedargmeth'));
    }

    public function testReloadMethodWithArgAdded()
    {
        $this->loadBaseClass();
        $class = new ExampleClass();
        $this->assertEquals('original', $class->addArg());
        $this->overwriteBaseClass();

        $res = SMC::reload_method('ExampleClass', 'exampleArgDefault');
        $this->assertTrue($res);

        $this->assertEquals('changedargmeth', $class->exampleArgDefault('changedargmeth'));
    }
}
