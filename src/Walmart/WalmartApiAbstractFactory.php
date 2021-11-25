<?php


namespace rollun\Walmart;


use Interop\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use rollun\Walmart\Sdk\Base;
use Zend\ServiceManager\Factory\AbstractFactoryInterface;

/**
 * Class WalmartApiAbstractFactory
 *
 * @package rollun\Walmart
 */
class WalmartApiAbstractFactory implements AbstractFactoryInterface
{
    public const KEY = self::class;

    public const KEY_CLASS = 'class';

    public const KEY_DEBUG = 'debug';

    public const KEY_SANDBOX = 'sandbox';

    public const KEY_CLIENT_ID = 'clientId';

    public const KEY_CLIENT_SECRET = 'clientSecret';

    public const KEY_CACHE = 'cache';

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     *
     * @return bool
     */
    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $container->get("config");
        $serviceConfig = $config[static::KEY][$requestedName] ?? null;
        $className = $serviceConfig[static::KEY_CLASS] ?? $requestedName;

        if (is_a($className, Base::class, true)) {
            return true;
        }

        return false;
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get("config");
        $serviceConfig = $config[self::KEY][$requestedName] ?? null;
        $className = $serviceConfig[self::KEY_CLASS] ?? $requestedName;

        $loggerClass = $serviceConfig['logger'] ?? LoggerInterface::class;
        $logger = $container->has($loggerClass) ? $container->get($loggerClass) : null;
        $debug = $serviceConfig[self::KEY_DEBUG] ?? false;

        $sandbox = $serviceConfig[self::KEY_SANDBOX] ?? false;

        $clientId = $serviceConfig[self::KEY_CLIENT_ID] ?? getenv('WALMART_CLIENT_ID');
        $clientSecret = $serviceConfig[self::KEY_CLIENT_SECRET] ??  getenv('WALMART_CLIENT_SECRET');

        $cache = $serviceConfig[self::KEY_CACHE] ?? null;
        if (is_string($cache)) {
            $cache = $container->get($cache);
        }

        if (empty($cache)) {
            $cache = new SessionCache();
        }

        return new $className($clientId, $clientSecret, $cache, $logger, $debug, $sandbox);
    }
}