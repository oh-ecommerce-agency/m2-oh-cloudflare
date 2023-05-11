<?php

declare(strict_types=1);

namespace OH\Cloudflare\Model;

use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\Zones;
use Magento\Framework\Serialize\SerializerInterface;

class Client
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        SerializerInterface $serializer,
        ConfigProvider $configProvider
    ) {
        $this->serializer = $serializer;
        $this->configProvider = $configProvider;
    }

    /**
     * Purge everything
     */
    public function purgeAllCache(): array
    {
        $result = [];
        $adapter = $this->getAdapter();
        $zone = new Zones($adapter);

        foreach ($this->configProvider->getZoneIds() as $zoneId) {
            $result[] = $zone->cachePurgeEverything($zoneId);
        }

        return $result;
    }

    /**
     * Get available zones
     *
     * @return array
     */
    public function getZones(): array
    {
        try {
            $adapter = $this->getAdapter();
            $response = $adapter->get('zones');
            $response = $this->serializer->unserialize($response->getBody());
        } catch (\Exception $e) {
            return [
                'error' => ['Response error: %1', $e->getMessage()],
                'response' => $e->getTraceAsString()
            ];
        }

        return $response && $response['success'] ? $response['result'] : [];
    }

    private function getAdapter()
    {
        $key = new APIKey($this->configProvider->getEmail(), $this->configProvider->getAuthKey());
        return new Guzzle($key);
    }
}