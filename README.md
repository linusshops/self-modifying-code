# self-modifying-code
Requires [Runkit](http://php.net/manual/en/book.runkit.php) and [Reflection](http://php.net/manual/en/book.reflection.php)

SMC provides a convenient way to reload functionality while you are in a REPL or debug console. 

Ever test something in the console, and realize while you're debugging what needs to be changed? Normally, you'd have to terminate the session, losing any accumulated context from your console session. SMC allows you to reload a class or method definition in mid-execution.

Due to a limitation in runkit, if you are currently executing a class, you cannot reload the whole thing. However, you can reload individual methods as well.

:fire::fire::fire::fire::fire::fire:

This is ONLY intended to be used as a debugging aid or in a REPL environment. 
If you are using this to implement functionality, you need to seriously re-evaluate your decisions.

:fire::fire::fire::fire::fire::fire:

## Installation
If runkit is not installed, install it with `pecl install runkit`.

Add `linusshops/runkit` to your composer.json.

## Usage
All reload methods return a boolean indicating if SMC was able to redefine the requested identifier.

All reload methods are able to accept either a string class definition, or an instance of the class itself.

### Reload a class
```
SMC::reload_class('SomeClassName')
```

### Reload a class method
```
SMC::reload_method('SomeClassName', 'methodName')
```
