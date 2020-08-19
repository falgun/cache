<?php
declare(strict_types=1);

namespace Falgun\Cache\Adapters;

class NoCache implements AdapterInterface
{

    public function has(string $key): bool
    {
        return false;
    }

    public function get(string $key, $default = null)
    {
        
    }

    public function set(string $key, $value, int $ttl = 3600)
    {
        
    }

    public function delete(string $key)
    {
        
    }

    public function flush()
    {
        
    }
}
