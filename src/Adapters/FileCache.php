<?php
declare(strict_types=1);

namespace Falgun\Cache\Adapters;

class FileCache implements AdapterInterface
{

    protected string $directory;

    public function __construct(string $directory)
    {
        $this->directory = $directory;
        $this->connect();
    }

    protected function connect(): void
    {
        $this->checkDIR($this->directory);
    }

    protected function checkDIR(string $directory): void
    {
        if (\is_dir($directory) === false) {
            \mkdir($directory, 0755, true);
        }
    }

    public function has(string $key): bool
    {
        return $this->get($key, false) !== false;
    }

    public function get(string $key, $deafult = null)
    {
        $path = $this->cacheFileFromKey($key);

        if (\file_exists($path)) {
            return $this->read($path, $deafult);
        }

        return $deafult;
    }

    public function set(string $key, $value, int $ttl = 3600)
    {
        $path = $this->cacheFileFromKey($key);
        $this->checkDIR(\dirname($path));

        $write = $this->write($path, $value, $ttl);

        if ($write !== false) {
            return true;
        }
        throw new \Exception("Couldn't write to cache !");
    }

    public function delete(string $key)
    {
        $file = $this->cacheFileFromKey($key);

        if (\file_exists($file)) {
            return \unlink($file);
        }

        return false;
    }

    public function flush($subDirectory = false)
    {
        if ($subDirectory !== false) {
            $directory = $this->directory . DS . $subDirectory;
        } else {
            $directory = $this->directory;
        }

        if (\is_dir($directory) === false) {
            return true;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                \unlink($file->getPathname());
            }
        }

        return true;
    }

    protected function cacheFileFromKey(string $key)
    {
        return $this->directory . DS . $key . '.cache';
    }

    /**
     * 
     * @param string $file
     * @param mixed $payload
     * @return type
     */
    protected function write(string $file, $payload, int $ttl)
    {
        $cache = $this->pack($payload);
        $expireAt = \time() + $ttl;

        return \file_put_contents($file, $expireAt . PHP_EOL . $cache, LOCK_EX);
    }

    /**
     * 
     * @param string $file
     * @return mixed
     */
    protected function read(string $file, $default = null)
    {
        $content = \file_get_contents($file);
        $lines = \explode(PHP_EOL, $content);

        if (\intval($lines[0]) >= \time()) {
            unset($lines[0]);
            $payload = \implode(\PHP_EOL, $lines);

            return $this->unpack($payload);
        }

        return $default;
    }

    /**
     * serialize data into string
     * @param mixed $data
     * @return string
     */
    protected function pack($data): string
    {
        return \serialize($data);
    }

    /**
     * deserialize string into php data
     * @param string $payload
     * @return mixed
     */
    protected function unpack(string $payload)
    {
        return \unserialize($payload);
    }
}
