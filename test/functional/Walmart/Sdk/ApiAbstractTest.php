<?php

namespace test\functional\Walmart\Sdk;

use PHPUnit\Framework\TestCase;
use rollun\Walmart\Sdk\Orders;
use rollun\Walmart\WalmartApiAbstractFactory;

abstract class ApiAbstractTest extends TestCase
{
    protected $api;

    protected function setUp()
    {
        $this->api = getTestContainer([
            WalmartApiAbstractFactory::KEY => [
                $this->getApiClass() => [
                    WalmartApiAbstractFactory::KEY_CLASS => $this->getApiClass(),
                    WalmartApiAbstractFactory::KEY_SANDBOX => true,
                    WalmartApiAbstractFactory::KEY_CLIENT_ID => getenv('SANDBOX_WALMART_CLIENT_ID'),
                    WalmartApiAbstractFactory::KEY_CLIENT_SECRET => getenv('SANDBOX_WALMART_CLIENT_SECRET'),
                ]
            ]
        ])->get($this->getApiClass());
    }

    abstract protected function getApiClass(): string;
}