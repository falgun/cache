<?php
declare(strict_types=1);

namespace Falgun\Cache\Adapters;

class JsonCache extends FileCache
{

    protected function cacheFileFromKey(string $key)
    {
        return $this->directory . DS . $key . '.json';
    }

    protected function pack($data): string
    {
        return \json_encode($data);
    }

    protected function unpack(string $payload)
    {
        return \json_decode($payload);
    }
}
