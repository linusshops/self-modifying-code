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
    public static function reload_class($class)
    {
        $ref = new ReflectionClass($class);

        $filename = $ref->getFileName();

        return runkit_import($filename, RUNKIT_IMPORT_OVERRIDE|RUNKIT_IMPORT_CLASS_METHODS);
    }

    public static function reload_method($classname, $methodname, $args = array())
    {
        $args = implode(',', $args);

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
            $args,
            self::getMethodCode($classname, $methodname),
            $visibility
        );
    }

    /**
     * Refresh SMC itself
     */
    public static function selfRefresh()
    {
        $reflection = new ReflectionClass(self::class);

    }

    public static function getMethodCode($class, $method)
    {
        $method = new ReflectionMethod($class, $method);
        $filename = $method->getFileName();
        $start_line = $method->getStartLine() + 1;
        $end_line = $method->getEndLine()-1;
        $length = $end_line - $start_line;

        $source = file($filename);
        return implode("", array_slice($source, $start_line, $length));
    }
}
