<?php


namespace test\integration\Walmart;


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
        $container = getTestContainer([
            WalmartApiAbstractFactory::KEY => [
                Price::class => [
                    WalmartApiAbstractFactory::KEY_DEBUG => true,
                    WalmartApiAbstractFactory::KEY_SANDBOX => true,
                    WalmartApiAbstractFactory::KEY_CLIENT_ID => getenv('SANDBOX_WALMART_CLIENT_ID'),
                    WalmartApiAbstractFactory::KEY_CLIENT_SECRET => getenv('SANDBOX_WALMART_CLIENT_SECRET'),
                ]
            ]
        ]);

        $factory = new WalmartApiAbstractFactory();
        $price = $factory($container, Price::class);

        $this->assertInstanceOf(Price::class, $price);
        $this->assertTrue($price->isDebug());
        $this->assertTrue($price->isSandbox());
    }
}