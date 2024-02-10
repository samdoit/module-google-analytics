<?php
/**
 * Copyright © Samdoit (support@samdoit.com). All rights reserved.
 * Please visit Samdoit.com for license details (https://www.samdoit.com/end-user-license-agreement).
 */
declare(strict_types=1);

namespace Samdoit\GoogleAnalytics\Block;

use Magento\Cookie\Helper\Cookie;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Samdoit\GoogleAnalytics\Model\Config\AnalyticsConfig;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * GoogleAnalytics Page Block
 */
class GoogleAnalytics extends Template
{
    /**
     * Google Analytics data
     *
     * @var AnalyticsConfig
     */
    private $_googleAnalyticsConfig;

    /**
     * @var OrderRepositoryInterface
     */
    private $_salesOrderRepository;

    /**
     * @var Cookie
     */
    private $_cookieHelper;

    /**
     * @var SerializerInterface
     */
    private $_serializer;

    /**
     * @var SearchCriteriaBuilder
     */
    private $_searchCriteriaBuilder;

    /**
     * @param Context                  $context
     * @param AnalyticsConfig          $googleAnalyticsConfig
     * @param Cookie                   $cookieHelper
     * @param SerializerInterface      $serializer
     * @param SearchCriteriaBuilder    $searchCriteriaBuilder
     * @param OrderRepositoryInterface $orderRepository
     * @param array                    $data
     */
    public function __construct(
        Context $context,
        AnalyticsConfig $googleAnalyticsConfig,
        Cookie $cookieHelper,
        SerializerInterface $serializer,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        $this->_googleAnalyticsConfig = $googleAnalyticsConfig;
        $this->_cookieHelper = $cookieHelper;
        $this->_serializer = $serializer;
        $this->_salesOrderRepository = $orderRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($context, $data);
    }

    /**
     * Get a specific page name (may be customized via layout)
     *
     * @return string|null
     */
    public function getPageName(): ?string
    {
        return $this->_getData('page_name');
    }

    /**
     * Return cookie restriction mode value.
     *
     * @return bool
     */
    public function isCookieRestrictionModeEnabled(): bool
    {
        return (bool) $this->_cookieHelper->isCookieRestrictionModeEnabled();
    }

    /**
     * Return current website id.
     *
     * @return int
     */
    public function getCurrentWebsiteId(): int
    {
        return (int) $this->_storeManager->getWebsite()->getId();
    }

    /**
     * Return information about page for GA tracking
     *
     * @link https://developers.google.com/analytics/devguides/collection/gtagjs
     * @link https://developers.google.com/analytics/devguides/collection/ga4
     *
     * @param  string $measurementId
     * @return array
     */
    public function getPageTrackingData($measurementId): array
    {
        return [
            'optPageUrl' => $this->getOptPageUrl(),
            'measurementId' => $this->escapeHtmlAttr($measurementId, false)
        ];
    }

    /**
     * Return information about order and items for GA tracking.
     *
     * @link https://developers.google.com/analytics/devguides/collection/ga4/ecommerce#purchase
     * @link https://developers.google.com/gtagjs/reference/ga4-events#purchase
     * @link https://developers.google.com/analytics/devguides/collection/gtagjs/enhanced-ecommerce#product-data
     * @link https://developers.google.com/gtagjs/reference/event#purchase
     *
     * @return array
     */
    public function getOrdersTrackingData(): array
    {
        $result = [];
        $orderIds = $this->getOrderIds();
        if (empty($orderIds) || !is_array($orderIds)) {
            return $result;
        }
        $this->_searchCriteriaBuilder->addFilter(
            'entity_id',
            $orderIds,
            'in'
        );
        $collection = $this->_salesOrderRepository->getList($this->_searchCriteriaBuilder->create());

        foreach ($collection->getItems() as $order) {
            foreach ($order->getAllVisibleItems() as $item) {
                $result['products'][] = [
                    'item_id' => $this->escapeJsQuote($item->getSku()),
                    'item_name' =>  $this->escapeJsQuote($item->getName()),
                    'price' => number_format((float) $item->getPrice(), 2),
                    'quantity' => (int)$item->getQtyOrdered(),
                ];
            }
            $result['orders'][] = [
                'transaction_id' =>  $order->getIncrementId(),
                'affiliation' => $this->escapeJsQuote($this->_storeManager->getStore()->getFrontendName()),
                'value' => number_format((float) $order->getGrandTotal(), 2),
                'tax' => number_format((float) $order->getTaxAmount(), 2),
                'shipping' => number_format((float) $order->getShippingAmount(), 2),
            ];
            $result['currency'] = $order->getOrderCurrencyCode();
        }
        return $result;
    }

    /**
     * Return page url for tracking.
     *
     * @return string
     */
    private function getOptPageUrl(): string
    {
        $optPageURL = '';
        $pageName = $this->getPageName() !== null ? trim($this->getPageName()) : '';
        if ($pageName && substr($pageName, 0, 1) === '/' && strlen($pageName) > 1) {
            $optPageURL = ", '" . $this->escapeHtmlAttr($pageName, false) . "'";
        }
        return $optPageURL;
    }

    /**
     * Provide analytics events data
     *
     * @return bool|string
     */
    public function getAnalyticsData()
    {
        $analyticData = [
            'isCookieRestrictionModeEnabled' => $this->isCookieRestrictionModeEnabled(),
            'currentWebsite' => $this->getCurrentWebsiteId(),
            'cookieName' => Cookie::IS_USER_ALLOWED_SAVE_COOKIE,
            'pageTrackingData' => $this->getPageTrackingData($this->_googleAnalyticsConfig->getMeasurementId()),
            'ordersTrackingData' => $this->getOrdersTrackingData(),
            'googleAnalyticsAvailable' => $this->_googleAnalyticsConfig->isAvailable()
        ];
        return $this->_serializer->serialize($analyticData);
    }
}
