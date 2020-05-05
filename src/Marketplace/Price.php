<?php
declare(strict_types=1);

namespace rollun\walmart\Marketplace;

/**
 * Class Price
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Price extends Base
{
    /**
     * @param string $sku
     * @param float  $amount
     * @param string $currency
     *
     * @return array
     * @throws \Exception
     */
    public function updateRegularPrice(string $sku, float $amount, string $currency = 'USD'): array
    {
        // prepare request data
        $data = [
            'sku'     => $sku,
            'pricing' => [
                [
                    'currentPriceType' => 'BASE',
                    'currentPrice'     => [
                        'currency' => $currency,
                        'amount'   => $amount
                    ]
                ]
            ]
        ];

        return $this->request('price', 'PUT', $data);
    }
}
