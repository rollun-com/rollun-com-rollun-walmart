<?php
declare(strict_types=1);

namespace rollun\walmart\Marketplace;

/**
 * Class Inventory
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Inventory extends Base
{
    /**
     * @param string $sku
     * @param string $shipNode
     *
     * @return array
     */
    public function getInventory(string $sku = '', string $shipNode = ''): array
    {
        // prepare url
        $url = $this->baseUrl . "inventory?shipNode=$shipNode&sku=$sku";

        $ch = curl_init();
        $options = [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_HEADER         => false,
            CURLOPT_HTTPGET        => true,
            CURLOPT_HTTPHEADER     => [
                "WM_SVC.NAME: " . self::NAME,
                "WM_QOS.CORRELATION_ID: " . $this->correlationId,
                "WM_SVC.VERSION: " . self::VERSION,
                "Authorization: Basic " . $this->authHash,
                "WM_SEC.ACCESS_TOKEN: " . $this->getToken(),
                "Content-Type: application/json",
                "Accept: application/json"
            ]
        ];
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);

        return json_decode($response, true);
    }
}
