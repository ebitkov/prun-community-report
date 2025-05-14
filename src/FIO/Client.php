<?php

namespace App\FIO;

use App\Entity\FIO\CSV;
use App\FIO\Resource\Global\WorkforceNeed;
use App\FIO\Resource\Infrastructure;
use App\FIO\Resource\Material;
use App\FIO\Resource\Planet;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class Client
{
    public function __construct(
        private HttpClientInterface $fioClient,
        private SerializerInterface $serializer,
    ) {
    }


    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function get(
        string $url,
        string $type,
        string $format = 'json',
        array $requestOptions = [],
        array $deserializationContext = []
    ): mixed {
        $response = $this->fioClient->request('GET', $url, $requestOptions);
        $data = $this->serializer->deserialize($response->getContent(), $type, $format, $deserializationContext);

        if (is_array($data)) {
            return new ArrayCollection($data);
        }
        return $data;
    }

    /**
     * @return ArrayCollection<int, Planet>
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPlanets(): ArrayCollection
    {
        return $this->get('/planet/allplanets', Planet::class . '[]', requestOptions: [
            'headers' => [
                'accept' => 'application/json'
            ]
        ]);
    }

    public function getPlanet(string $id, ?Planet $objectToHydrate = null): ?Planet
    {
        return $this->get(
            "/planet/$id",
            Planet::class,
            requestOptions: [
                'headers' => [
                    'accept' => 'application/json'
                ]
            ],
            deserializationContext: [
                AbstractNormalizer::OBJECT_TO_POPULATE => $objectToHydrate,
            ]
        );
    }

    /**
     * @return ArrayCollection<int, Material>
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getMaterials(): ArrayCollection
    {
        return $this->get(
            '/material/allmaterials',
            Material::class . '[]',
            requestOptions: [
                'headers' => [
                    'accept' => 'application/json'
                ]
            ]
        );
    }

    /**
     * @return ArrayCollection<int, Planet\Site>
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getPlanetSites(string $planet): ArrayCollection
    {
        return $this->get(
            "/planet/sites/$planet",
            Planet\Site::class . '[]',
            requestOptions: [
                'headers' => [
                    'accept' => 'application/json'
                ]
            ]
        );
    }

    /**
     * @return ArrayCollection<int, Infrastructure>
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getInfrastructureReport(string $id): ArrayCollection
    {
        return $this->get(
            "/infrastructure/$id",
            Infrastructure::class . '[]',
            requestOptions: [
                'headers' => [
                    'accept' => 'application/json'
                ]
            ]
        );
    }

    /**
     * @return ArrayCollection<int, WorkforceNeed>
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getWorkforceNeeds():ArrayCollection
    {
        return $this->get(
            '/global/workforceneeds',
            WorkforceNeed::class . '[]',
            requestOptions: [
                'headers' => [
                    'accept' => 'application/json'
                ]
            ]
        );
    }
}