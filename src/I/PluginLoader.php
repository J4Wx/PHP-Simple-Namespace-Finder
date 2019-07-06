<?php

namespace J4Wx\SimpleNamespaceFinder\I;

interface PluginLoader
{
    public static function find(string $namespace, int $depth);
}
