<?php

/**
 *
 *
 * @author Sam Schmidt <samuel@dersam.net>
 * @since 2015-12-11
 * @company Linus Shops
 */
class ExampleClass
{
    function example()
    {
        return "exampleclass";
    }

    function exampleArg($arg)
    {
        return 'original';
    }

    function exampleArgDefault($arg =null)
    {
        return 'original';
    }

    function addArg()
    {
        return 'original';
    }
}
