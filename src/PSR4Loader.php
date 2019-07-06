<?php

namespace J4Wx\SimpleNamespaceFinder;

use J4Wx\SimpleNamespaceFinder\I\PluginLoader;

class PSR4Loader implements PluginLoader
{
    public static function find(string $namespace, int $depth = 0)
    {
        $initial = self::getNamespaces();
        $continue = [];

        foreach ($initial as $i => $n) {
            if (substr($namespace, 0, strlen($i)) === $i) {
                $continue[$i] = $n;
            } elseif (substr($i, 0, strlen($namespace)) === $namespace) {
                $continue[$i] = $n;
            }
        }

        $output = [];

        foreach ($continue as $c => $n) {
            $nameArray = explode($c, $namespace);
            array_shift($nameArray);
            $nameSuffix = implode('', $nameArray);

            foreach ($n as $x) {
                $dir = $x . DIRECTORY_SEPARATOR . $nameSuffix;
                $output = array_merge($output, self::loadNamespace($dir, $c . $nameSuffix));
            }
        }

        return $output;
    }

    private static function getNamespaces()
    {
        return array_merge(self::getUserNamespaces(), self::getVendorNamespaces());
    }

    private static function getUserNamespaces()
    {

        if (file_exists(getcwd() . DIRECTORY_SEPARATOR . 'composer.json')) {
            $comp = json_decode(getcwd() . DIRECTORY_SEPARATOR . 'composer.json');
            if (isset($comp->autoload)) {
                // Oh, php...
                $psr = "psr-4";
                if (isset($comp->autoload->$psr)) {
                    return $comp->autoload->$psr;
                }
            }
        }

        return [];
    }

    private static function getVendorNamespaces()
    {
        $psrAutoload = getcwd() . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'composer' . DIRECTORY_SEPARATOR . 'autoload_psr4.php';
        if (file_exists($psrAutoload)) {
            return require($psrAutoload);
        }

        return [];
    }

    private static function loadNamespace($dir, $namespace)
    {
        $directories = [];
        $Directory = new \RecursiveDirectoryIterator($dir);
        $Iterator = new \RecursiveIteratorIterator($Directory);
        $Regex = new \RegexIterator($Iterator, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH);

        foreach ($Regex as $i => $f) {
            $i = explode($dir, $i);
            array_shift($i);
            $i = implode('', $i);
            $i = substr($i, 0, strlen($i) - 4);
            $i = implode('\\', explode('/', $i));

            $directories[] = $namespace . $i;
        }

        return $directories;
    }
}
