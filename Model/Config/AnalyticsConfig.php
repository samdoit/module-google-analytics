<?php
/**
 * Copyright © Samdoit (support@samdoit.com). All rights reserved.
 * Please visit Samdoit.com for license details (https://www.samdoit.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Samdoit\GoogleAnalytics\Model\Config;

use Magento\Store\Model\Store;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class AnalyticsConfig
{
    /**
     * General config
     */
    public const XML_PATH_EXTENSION_ENABLED = 'samdoit_google/general/enabled';

    /**
     * Analytics config
     */
    public const XML_PATH_ANALYTICS_MEASUREMENT_ID = 'samdoit_google/analytics/measurement_id';
    public const XML_PATH_ANALYTICS_ANONYMIZE = 'samdoit_google/analytics/anonymize';

    /**
     * Product attributes config
     */
    public const XML_PATH_ATTRIBUTES_PRODUCT = 'samdoit_google/attributes/product';
    public const XML_PATH_ATTRIBUTES_BRAND = 'samdoit_google/attributes/brand';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Whether GA is ready to use
     *
     * @param  null|string|bool|int|Store $store
     * @return bool
     */
    public function isAvailable($store = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_EXTENSION_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $store
        ) && $this->getMeasurementId();
    }

    /**
     * Get Measurement Id (GA4)
     *
     * @return string
     */
    public function getMeasurementId(): string
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_ANALYTICS_MEASUREMENT_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Whether anonymized IPs are active
     *
     * @param null|string|bool|int|Store $store
     * 
     * @return bool
     */
    public function isAnonymizedIpActive($store = null): bool
    {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_ANALYTICS_ANONYMIZE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Retrieve Magento product attribute
     *
     * @param null|string|bool|int|Store $store
     * 
     * @return string
     */
    public function getProductAttribute($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_ATTRIBUTES_PRODUCT, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Retrieve Magento product brand attribute
     *
     * @param null|string|bool|int|Store $store
     * 
     * @return string
     */
    public function getBrandAttribute($store = null): string
    {
        return (string)$this->scopeConfig->getValue(self::XML_PATH_ATTRIBUTES_BRAND, ScopeInterface::SCOPE_STORE, $store);
    }
}
