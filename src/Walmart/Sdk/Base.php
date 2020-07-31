<?php
declare(strict_types=1);

namespace rollun\Walmart\Sdk;

use Psr\Log\LoggerInterface;
use rollun\dic\InsideConstruct;
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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Base constructor.
     */
    public function __construct(LoggerInterface $logger = null)
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

        InsideConstruct::init(['logger' => LoggerInterface::class]);
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return ['correlationId', 'baseUrl', 'authHash'];
    }

    /**
     * @throws \ReflectionException
     */
    public function __wakeup()
    {
        InsideConstruct::initWakeup(['logger' => LoggerInterface::class]);
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
                CURLOPT_FAILONERROR    => true,
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

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $this->logger->error(curl_error($ch));
            }

            curl_close($ch);

            $responseData = json_decode($response);

            if (!isset($responseData->access_token)) {
                $this->logger->error("Walmart Auth failed");
                return 'no-access-token';
            }

            $_SESSION['walmartAuth'] = [
                'access_token' => $responseData->access_token,
                'lifetime'     => time() + $responseData->expires_in
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
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->error(curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }
}
