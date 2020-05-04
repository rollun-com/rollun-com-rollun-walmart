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

        return $response->access_token;
    }
}
