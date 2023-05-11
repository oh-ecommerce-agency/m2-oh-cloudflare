<?php

declare(strict_types=1);

namespace OH\Cloudflare\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use OH\Cloudflare\Model\Client;
use OH\Cloudflare\Model\ConfigProvider;

/**
 * Observer used to purge cloudflare caches
 */
class PurgeCache implements ObserverInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ManagerInterface
     */
    private $messageManager;

    public function __construct(
        ManagerInterface $messageManager,
        Client $client,
        ConfigProvider $configProvider
    ) {
        $this->configProvider = $configProvider;
        $this->messageManager = $messageManager;
        $this->client = $client;
    }

    /**
     * Purge cloudflare cache
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        if ($this->validateConfig()) {
            $failed = 0;

            foreach ($this->client->purgeAllCache() as $res) {
                if (!$res) {
                    $failed++;
                }
            }

            if ($failed) {
                $this->messageManager->addErrorMessage('Error purging Cloudflare cache');
                return;
            }

            $this->messageManager->addSuccessMessage(__('Cloudflare cache purged'));
        }
    }

    /**
     * Validate mandatory configs to purge cache
     *
     * @return bool
     */
    private function validateConfig(): bool
    {
        return $this->configProvider->isEnabledPurgeCache() &&
            $this->configProvider->getAuthKey() &&
            $this->configProvider->getZoneIds() &&
            $this->configProvider->getEmail();
    }
}
