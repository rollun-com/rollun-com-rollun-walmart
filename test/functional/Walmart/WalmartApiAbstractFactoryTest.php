<?php


namespace test\functional\Walmart;


use PHPUnit\Framework\TestCase;
use rollun\logger\LifeCycleToken;
use rollun\Walmart\Sdk\Price;
use rollun\Walmart\WalmartApiAbstractFactory;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\ServiceManager;

class WalmartApiAbstractFactoryTest extends TestCase
{
    public function testCreateApiInstance()
    {
        $container = $this->getContainer([
            WalmartApiAbstractFactory::KEY => [
                Price::class => [
                    'debug' => true,
                ]
            ]
        ]);

        $factory = new WalmartApiAbstractFactory();
        $price = $factory($container, Price::class);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertTrue($price->isDebug());
    }

    protected function getContainer($replaceConfig = [])
    {
        global $container;

        $config = $container->get('config');
        $config = array_merge($config, $replaceConfig);

        $cloned = new ServiceManager();
        (new Config($config['dependencies']))->configureServiceManager($cloned);
        $cloned->setService('config', $config);

        $lifeCycleToken = LifeCycleToken::generateToken();
        $cloned->setService(LifeCycleToken::class, $lifeCycleToken);

        return $cloned;
    }
}