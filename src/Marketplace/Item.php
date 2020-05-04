<?php
declare(strict_types=1);

namespace rollun\walmart\Marketplace;

/**
 * Class Item
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Item extends Base
{
    /**
     * @param string $sku
     * @param int    $limit
     * @param int    $offset
     * @param string $nextCursor
     *
     * @return array
     */
    public function getItems(string $sku = '', int $limit = 20, int $offset = 0, string $nextCursor = '*'): array
    {
        // prepare url
        $url = $this->baseUrl . "items?offset=$offset&limit=$limit&nextCursor=$nextCursor&sku=$sku";

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

//    public function postInventory($fileName)
//    {
//        $auth = json_decode($this->getToken(), true);
//        $token = $auth["access_token"];
//        $authorization = base64_encode($this->clientId . ":" . $this->clientSecret);
//        $qos = uniqid();
//
//        $url = "https://marketplace.walmartapis.com/v3/feeds?feedType=inventory";
//        $ch = curl_init();
//        $options = array(
//            CURLOPT_URL            => $url,
//            CURLOPT_RETURNTRANSFER => true,
//            CURLOPT_TIMEOUT        => 60,
//            CURLOPT_HEADER         => false,
//            CURLOPT_POST           => 1,
//            CURLOPT_POSTFIELDS     => array('file' => file_get_contents(dirname(__FILE__) . "/" . $fileName)), #'@' . dirname(__FILE__)."/".$fileName,
//            CURLOPT_HTTPHEADER     => array
//            (
//                "WM_SVC.NAME: Walmart Marketplace",
//                "WM_QOS.CORRELATION_ID: " . $qos,
//                "WM_SVC.VERSION: 1.0.0",
//                "Authorization: Basic " . $authorization,
//                "WM_SEC.ACCESS_TOKEN: " . $token,
//                "Content-Type: multipart/form-data",
//                "Accept: application/xml",
//                "Host: marketplace.walmartapis.com"
//            )
//        );
//        curl_setopt_array($ch, $options);
//        $response = curl_exec($ch);
//        return $response; //xml
//    }
}
