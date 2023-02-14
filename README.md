## Cloudflare Helper for Magento 2:
- Flush Cache
- Flush Cache Storage
- Flush JavaScript/CSS Cache
- Get IP directly from Cloudflare header HTTP_CF_CONNECTING_IP

**Works also for CLI**

Steps to configure
1) Enable the module
2) Complete Authentication email and key and **save**
3) After page reload, select zones to purge cache

**Get Auth key from https://dash.cloudflare.com/profile > API Tokens > Global API Key**

Module installation <br />
`composer require m2-oh/cloudflare`
