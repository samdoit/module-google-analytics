<?php
/**
 * Copyright © Samdoit (support@samdoit.com). All rights reserved.
 * Please visit Samdoit.com for license details (https://samdoit.com/end-user-license-agreement).
 */
namespace Samdoit\GoogleAnalytics\Helper;

use Magento\Store\Model\Store;
use Magento\Store\Model\ScopeInterface;

/**
 * GoogleAnalytics data helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Config paths for using throughout the code
     */
    public const XML_PATH_ACTIVE = 'samdoit_google/analytics/active';

    public const XML_PATH_ACCOUNT = 'samdoit_google/analytics/account';

    public const XML_PATH_ANONYMIZE = 'samdoit_google/analytics/anonymize';

    /**
     * Whether GA4 is ready to use
     *
     * @param null|string|bool|int|Store $store
     * 
     * @return bool
     */
    public function isGoogleAnalyticsAvailable($store = null)
    {
        $accountId = $this->scopeConfig->getValue(self::XML_PATH_ACCOUNT, ScopeInterface::SCOPE_STORE, $store);
        return $accountId && $this->scopeConfig->isSetFlag(self::XML_PATH_ACTIVE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Whether anonymized IPs are active
     *
     * @param null|string|bool|int|Store $store
     * 
     * @return bool
     */
    public function isAnonymizedIpActive($store = null)
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_ANONYMIZE, ScopeInterface::SCOPE_STORE, $store);
    }
}
