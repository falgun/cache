<?php
namespace Falgun\Cache\Adapters;

interface AdapterInterface
{

    public function has(string $key):bool;

    public function get(string $key, $default = null);

    public function set(string $key, $value, int $ttl = 3600);

    public function delete(string $key);

    public function flush();
}
