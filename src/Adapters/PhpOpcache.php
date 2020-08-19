<?php
declare(strict_types=1);

namespace Falgun\Cache\Adapters;

class PhpOpcache extends FileCache
{

    protected function cacheFileFromKey(string $key)
    {
        return $this->directory . DS . $key . '.php';
    }

    protected function write(string $file, $payload, int $ttl)
    {
        $cache = $this->pack($payload);
        $expireAt = \time() + $ttl;

        return \file_put_contents($file,
            '<?php if(' . $expireAt . ' >= time()){' .
            PHP_EOL . ' return ' . $cache . ';' .
            PHP_EOL . '} else { return null; }',
            LOCK_EX);
    }

    protected function read(string $file, $default = null)
    {
        $cache = (require $file);

        if ($cache !== null) {
            return $cache;
        }
        return $default;
    }

    protected function pack($data): string
    {
        return var_export($data, true);
    }

    protected function unpack(string $payload)
    {
        
    }
}
