<?php

/**
 * Copyright © Samdoit (support@samdoit.com). All rights reserved.
 * Please visit Samdoit.com for license details (https://www.samdoit.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Samdoit\GoogleAnalytics\Block\Adminhtml\System\Config\Form;

class Info extends \Samdoit\Community\Block\Adminhtml\System\Config\Form\Info
{
    /**
     * Return extension url
     *
     * @return string
     */
    protected function getModuleUrl(): string
    {
        return 'https://sam' . 'do' .
            'it.com/' . 'prod' . 'uct/mage' . 'nto.ht' . 'ml/' . 'mage' . 'nto-2.htm' . 'l?utm_source=Google' . 'Analytics' . 'Config&utm_medium=link&utm_campaign=regular';
    }

    /**
     * Return extension title
     *
     * @return string
     */
    protected function getModuleTitle(): string
    {
        return 'Google Analytics Extension';
    }
}
