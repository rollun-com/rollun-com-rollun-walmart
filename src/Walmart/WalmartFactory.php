<?php


namespace rollun\Walmart;


use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class WalmartFactory implements FactoryInterface
{
    public const KEY = self::class;

    public const KEY_APIS = 'apis';

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config')[self::KEY] ?? [];
        $apis = $config[self::KEY_APIS] ?? [];
        foreach ($apis as $key => $value) {
            $instance = $container->get($value);
            $key = strtolower($key);
            $apis[$key] = $instance;

            $class = get_class($instance);
            if ($key != $class) {
                $apis[$class] = $instance;
            }
        }

        return new Walmart($apis);
    }
}