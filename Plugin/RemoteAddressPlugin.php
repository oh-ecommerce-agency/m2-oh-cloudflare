<?php

namespace OH\Cloudflare\Plugin;

use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;

class RemoteAddressPlugin
{
    /**
     * Get the right address from cloudflare header
     *
     * @param RemoteAddress $subject
     * @param string $result
     * @param bool $ipToLong
     * @return string
     */
    public function afterGetRemoteAddress(RemoteAddress $subject, string $result, bool $ipToLong = false)
    {
        if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $result = $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        return $result;
    }
}