<?php

declare(strict_types=1);

namespace App\Service\ResponseCRM;

use App\Model\Utility\Entity\Clients\Client\SecretKey;
use App\Service\ResponseCRM\Contract\GetSitesResponse;
use App\Service\ResponseCRM\Contract\TestAuthResponse;
use App\Service\ResponseCRM\Contract\Type\Site;
use App\Service\ResponseCRM\Exception\ServerException;
use App\Service\ResponseCRM\Exception\UnauthorizedAccessException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Api
{
    public const API_URL = 'https://openapi.responsecrm.com/api/v2/open';

    /**
     * @var SecretKey
     */
    private $secretKey;

    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * Api constructor.
     * @param SecretKey $secretKey
     */
    public function __construct(SecretKey $secretKey)
    {
        $this->secretKey = $secretKey;
        $this->serializer = new Serializer(
            [new ObjectNormalizer(null, null, null, new ReflectionExtractor()), new ArrayDenormalizer()],
            [new JsonEncoder()]
        );
    }

    /**
     * @return TestAuthResponse
     * @throws ClientExceptionInterface
     */
    public function testAuth(): TestAuthResponse
    {
        $response = $this->makeRequest('GET', '/test-auth');

        return $this->serializer->deserialize($response, TestAuthResponse::class, 'json');
    }

    /**
     * @return GetSitesResponse
     */
    public function getSites(): GetSitesResponse
    {
        $response = $this->makeRequest('GET', '/sites');

        return $this->serializer->deserialize($response, GetSitesResponse::class, 'json');
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $options
     * @return string
     */
    private function makeRequest(string $method, string $path, array $options = []): string
    {
        if ($this->httpClient === null) {
            $this->httpClient = HttpClient::create(['headers' => [
                'Authorization' => 'ApiKey ' . $this->secretKey->getValue()
            ]]);
        }

        try {
            $response = $this->httpClient->request($method, $this->getUrl($path), $options);

            return $response->getContent();
        } catch (ClientExceptionInterface $e) {
            if ($e->getCode() === 401)  {
                throw new UnauthorizedAccessException('Key "' .  $this->secretKey . '" did not authenticate in ResponseCRM.', 401, $e);
            }

            throw new ClientException($e->getResponse());
        } catch (\Throwable  $e) {
            throw new ServerException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param string $path
     * @return string
     */
    private function getUrl(string $path): string
    {
        return self::API_URL . $path;
    }
}
