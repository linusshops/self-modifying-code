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
    private static $modifiedMethods = array();

    public static function reload_class($class)
    {
        $ref = new ReflectionClass($class);

        $filename = $ref->getFileName();

        return runkit_import($filename, RUNKIT_IMPORT_OVERRIDE|RUNKIT_IMPORT_CLASS_METHODS);
    }

    /**
     * Runkit changes the source file of a method, so we have to track methods
     * once we modify them, otherwise SMC crashes after first reload.
     * @param $classname
     * @param $methodname
     * @param $filename
     */
    private static function trackModifiedMethod($classname, $methodname, $filename)
    {
        if (!isset(self::$modifiedMethods[$classname])) {
            self::$modifiedMethods[$classname] = array();
        }

        if (!isset(self::$modifiedMethods[$classname][$methodname])) {
            self::$modifiedMethods[$classname][$methodname] = array();
        }

        self::$modifiedMethods[$classname][$methodname] = $filename;
    }

    private static function getTrackedMethodFilename($classname, $methodname)
    {
        return
        isset(self::$modifiedMethods[$classname])
        && isset(self::$modifiedMethods[$classname][$methodname])
        ?
            self::$modifiedMethods[$classname][$methodname]
            : false;
    }

    public static function reload_method($classname, $methodname)
    {
        $method = new ReflectionMethod($classname, $methodname);
        $visibility = RUNKIT_ACC_PUBLIC;

        if ($method->isProtected()) {
            $visibility = RUNKIT_ACC_PROTECTED;
        } else if ($method->isPrivate()) {
            $visibility = RUNKIT_ACC_PRIVATE;
        }

        if ($method->isStatic()) {
            $visibility = $visibility | RUNKIT_ACC_STATIC;
        }

        return runkit_method_redefine(
            $classname,
            $methodname,
            self::getMethodArguments($classname, $methodname),
            self::getMethodCode($classname, $methodname),
            $visibility
        );
    }

    private static function getMethodArguments($class, $method)
    {
        $method = new ReflectionMethod($class, $method);
        $methodname = $method->name;
        $classname = $method->class;
        if (! $filename =
            self::getTrackedMethodFilename($classname, $methodname)) {
            $filename = $method->getFileName();
            self::trackModifiedMethod($classname, $methodname, $filename);
        }

        //Ensure that the method signature is on one line
        $source = file_get_contents($filename);
        $source = str_replace("\n", "", $source);
        $source = str_replace("\r", "", $source);

        //Get the args
        $regex = '/function '.$methodname.'\(([\s\S]*?)\)/';
        $args = array();
        preg_match($regex, $source, $args);

        if (empty($args)) {
            $args = '';
        } else {
            $args = str_replace(' ', '',$args[1]);
        }

        return $args;
    }

    private static function getMethodCode($class, $method)
    {
        $method = new ReflectionMethod($class, $method);
        $methodname = $method->name;
        $classname = $method->class;
        if (! $filename =
            self::getTrackedMethodFilename($classname, $methodname)) {
            $filename = $method->getFileName();
            self::trackModifiedMethod($classname, $methodname, $filename);
        }

        $source = file_get_contents($filename);

        //This regex will find the method body
        $regex = '/function '.$methodname.'*?[\s\S]*?\{([\s\S]*?)\}/';
        $body = array();
        preg_match($regex, $source, $body);

        if (empty($body)) {
            $body = '';
        } else {
            $body = $body[1];
        }

        return $body;
    }
}
