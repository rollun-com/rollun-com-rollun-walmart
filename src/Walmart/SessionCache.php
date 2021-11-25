<?php

namespace rollun\Walmart;



use Psr\SimpleCache\CacheInterface;

class SessionCache implements CacheInterface
{
    protected const KEY_CACHE = 'cache';

    protected const KEY_VALUE = 'value';

    protected const KEY_EXPIRE = 'expire';

    protected $defaultTtl;

    public function __construct($ttl = 300)
    {
        $this->defaultTtl = $ttl;
    }

    protected function startSession()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    protected function isValid($timestamp)
    {
        $currenDate = new \DateTime('now');
        return $currenDate->getTimestamp() < $timestamp;
    }

    public function get($key, $default = null)
    {
        $this->startSession();
        if ($this->has($key)) {
            return $_SESSION[self::KEY_CACHE][$key][self::KEY_VALUE];
        }

        return $default;
    }

    public function set($key, $value, $ttl = null)
    {
        $this->startSession();
        $ttl = $ttl ?? $this->defaultTtl;
        $item = [
            self::KEY_VALUE => $value,
            self::KEY_EXPIRE => (new \DateTime())->modify('+' . $ttl . ' seconds')->getTimestamp()
        ];
        $_SESSION[self::KEY_CACHE][$key] = $item;
    }

    public function has($key)
    {
        $this->startSession();
        if (isset($_SESSION[self::KEY_CACHE][$key])) {
            $item = $_SESSION[self::KEY_CACHE][$key];
            return $this->isValid($item[self::KEY_EXPIRE]);
        }

        return false;
    }

    public function delete($key)
    {
        $this->startSession();
        unset($_SESSION[self::KEY_CACHE][$key]);
    }

    public function clear()
    {
        $this->startSession();
        $_SESSION[self::KEY_CACHE] = [];
    }

    public function getMultiple($keys, $default = null)
    {
        throw new \Exception('Not implement');
    }

    public function setMultiple($values, $ttl = null)
    {
        throw new \Exception('Not implement');
    }

    public function deleteMultiple($keys)
    {
        throw new \Exception('Not implement');
    }
}