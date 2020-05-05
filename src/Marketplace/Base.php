<?php
declare(strict_types=1);

namespace rollun\walmart\Marketplace;

use Zend\ServiceManager\Exception\InvalidArgumentException;

/**
 * Class Base
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Base
{
    public const API_URL = 'https://marketplace.walmartapis.com/v3/';
    public const SANDBOX_API_URL = 'https://sandbox.walmartapis.com/v3/';
    public const NAME = 'Walmart Marketplace';
    public const VERSION = '1.0.0';

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $correlationId;

    /**
     * @var string
     */
    protected $authHash;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        $this->correlationId = uniqid();

        $clientId = getenv('WALMART_CLIENT_ID');
        $clientSecret = getenv('WALMART_CLIENT_SECRET');

        if (empty($clientId) || empty($clientSecret)) {
            throw new InvalidArgumentException("CLIENT_ID and CLIENT_SECRET is required");
        }

        if (empty(getenv('WALMART_SANDBOX'))) {
            $this->baseUrl = self::API_URL;
        } else {
            $this->baseUrl = self::SANDBOX_API_URL;
        }

        $this->authHash = base64_encode("$clientId:$clientSecret");
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        if (empty($_SESSION['walmartAuth']) || $_SESSION['walmartAuth']['lifetime'] <= time()) {
            $ch = curl_init();
            $options = [
                CURLOPT_URL            => $this->baseUrl . "token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 60,
                CURLOPT_HEADER         => false,
                CURLOPT_POST           => 1,
                CURLOPT_POSTFIELDS     => "grant_type=client_credentials",
                CURLOPT_HTTPHEADER     => [
                    "Authorization: Basic " . $this->authHash,
                    "Content-Type: application/x-www-form-urlencoded",
                    "Accept: application/json",
                    "WM_SVC.NAME: " . self::NAME,
                    "WM_QOS.CORRELATION_ID: " . $this->correlationId,
                    "WM_SVC.VERSION: " . self::VERSION
                ]
            ];
            curl_setopt_array($ch, $options);
            $response = json_decode(curl_exec($ch));

            if (!isset($response->access_token)) {
                throw new \Exception("Walmart Auth failed");
            }

            $_SESSION['walmartAuth'] = [
                'access_token' => $response->access_token,
                'lifetime'     => time() + $response->expires_in
            ];
        }

        return $_SESSION['walmartAuth']['access_token'];
    }

    /**
     * @param string $path
     * @param string $method
     * @param array  $data
     *
     * @return array
     * @throws \Exception
     */
    protected function request(string $path, string $method = 'GET', array $data = []): array
    {
        $ch = curl_init();

        // prepare headers
        $headers = [
            "WM_SVC.NAME: " . self::NAME,
            "WM_QOS.CORRELATION_ID: " . $this->correlationId,
            "WM_SVC.VERSION: " . self::VERSION,
            "WM_SEC.ACCESS_TOKEN: " . $this->getToken(),
            "Authorization: Basic " . $this->authHash,
            "Content-Type: application/json",
            "Accept: application/json"
        ];

        if (!empty($data)) {
            $jsonData = json_encode($data);
            $headers[] = "Content-Length: " . strlen($jsonData);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        }

        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
