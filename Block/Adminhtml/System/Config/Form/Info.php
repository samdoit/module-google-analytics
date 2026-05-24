<?php

/**
 * Copyright © Samdoit (support@samdoit.com). All rights reserved.
 * Please visit Samdoit.com for license details (https://www.samdoit.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Samdoit\GoogleAnalytics\Block\Adminhtml\System\Config\Form;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Info extends Field
{
    /**
     * Render extension info link in the admin config field row.
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        return sprintf(
            '<a href="%s" target="_blank" rel="noopener noreferrer">%s</a>',
            $this->escapeUrl($this->getModuleUrl()),
            $this->escapeHtml($this->getModuleTitle())
        );
    }

    /**
     * @return string
     */
    protected function getModuleUrl(): string
    {
        return 'https://www.samdoit.com/product/magento.html/magento-2.html'
            . '?utm_source=GoogleAnalyticsConfig&utm_medium=link&utm_campaign=regular';
    }

    /**
     * @return string
     */
    protected function getModuleTitle(): string
    {
        return 'Google Analytics Extension';
    }
}
