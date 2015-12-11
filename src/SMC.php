<?php

/**
 *
 *
 * @author Sam Schmidt <samuel@dersam.net>
 * @since 2015-12-11
 * @company Linus Shops
 */
class SMC
{
    public static function reload($class)
    {
        $ref = new ReflectionClass($class);

        $filename = $ref->getFileName();

        return runkit_import($filename, RUNKIT_IMPORT_OVERRIDE|RUNKIT_IMPORT_CLASS_METHODS);
    }
}
