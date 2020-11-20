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

        return new $className($logger, $debug);
    }
}