# Simple Namespace Finder

Simple Namespace Finder is a library that can be used to find all of the classes within a specified namespace. It currently only supports PSR4 namespacing but I am open to adding other options if anyone requires it.

## Use Case

The use case for which I first built this library was a need to find all of an apps controllers to confirm that the one specified in the routes file existed before attempting to instantiate it. Rather than all of the controllers needing to be declared individually, the app can now find all of them manually.

## Example

```php
use J4Wx\SimpleNamespaceFinder\PSR4Loader;
$loader = PSR4Loader::find('J4Wx');
```

This code in this project would result in:

```php
$loader = [
    "J4Wx\SimpleNamespaceFinder\I\PluginLoader",
    "J4Wx\SimpleNamespaceFinder\PSR4Loader"
];
```

You can be more specific too:

```php
use J4Wx\SimpleNamespaceFinder\PSR4Loader;
$loader = PSR4Loader::find('J4Wx\SimpleNamespaceFinder\I');
```

Results in:

```php
$loader = [
    "J4Wx\SimpleNamespaceFinder\I\PluginLoader",
];
```

## Composer

This project is available on Packagist.

https://packagist.org/packages/j4wx/simple-namespace-finder

```
composer require j4wx/simple-namespace-finder
```
