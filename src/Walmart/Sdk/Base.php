<?php
declare(strict_types=1);

namespace rollun\Walmart\Sdk;

use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use rollun\dic\InsideConstruct;
use rollun\Walmart\SessionCache;
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
    private $correlationId;

    /**
     * @var string
     */
    protected $authHash;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var string
     */
    //private static $correlationId;

    protected $cache;

    protected $sandbox;

    /**
     * Base constructor.
     */
    public function __construct(
        string $clientId,
        string $clientSecret,
        CacheInterface $cache,
        LoggerInterface $logger = null,
        ?bool $debug = false,
        ?bool $sandbox = false
    ) {
        $this->correlationId = $this->getCorrelationId();

        $this->cache = $cache;
        $this->sandbox = $sandbox;

        if (!$this->sandbox) {
            $this->baseUrl = self::API_URL;
        } else {
            $this->baseUrl = self::SANDBOX_API_URL;
        }

        $this->authHash = base64_encode("$clientId:$clientSecret");

        $this->logger = $logger;
        $this->debug = $debug;
    }

    /**
     * @return string
     */
    protected function getCorrelationId()
    {
        if ($this->correlationId === null) {
            $this->correlationId = uniqid('', true);
        }

        return $this->correlationId;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return [
            'correlationId',
            'baseUrl',
            'authHash',
            'debug',
        ];
    }

    /**
     * @throws \ReflectionException
     */
    public function __wakeup()
    {
        InsideConstruct::initWakeup(['logger' => LoggerInterface::class]);
    }

    public function isDebug()
    {
        return $this->debug;
    }

    public function isSandbox()
    {
        return $this->sandbox;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        $key = 'authToken' . ($this->sandbox ? 'Sandbox' : 'Production');
        if (!$this->cache->has($key)) {
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
                    "WM_QOS.CORRELATION_ID: " . $this->getCorrelationId(),
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

            $this->cache->set('authToken', $responseData->access_token, $responseData->expires_in);

            return $responseData->access_token;
        }

        return $this->cache->get('authToken');
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
            "WM_QOS.CORRELATION_ID: " . $this->getCorrelationId(),
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
        } elseif (strtoupper($method) === 'POST') {
            $headers[] = "Content-Length: 0";
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        }

        curl_setopt($ch, CURLOPT_URL, $this->baseUrl . $path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->isDebug()) {
            $this->logger->debug('Walmart request', [
                'url' => $this->baseUrl . $path,
                'method' => $method,
                'headers' => $headers,
                'body' => $jsonData ?? null,
            ]);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $this->logger->error(curl_error($ch));
        }

        if ($this->isDebug()) {
            $this->logger->debug('Walmart response', [
                'response' => $response,
            ]);
        }

        curl_close($ch);

        $result = !empty($response) ? json_decode($response, true) : [];

        if (isset($result['errors'])) {
            $this->logger->error('Walmart API request failed', $result);
        }

        return $result;
    }
}
