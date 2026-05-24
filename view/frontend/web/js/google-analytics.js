/**
 * Copyright © Samdoit (support@samdoit.com). All rights reserved.
 * Please visit Samdoit.com for license details (http://www.samdoit.com/end-user-license-agreement).
 */
define([
    'jquery',
    'mage/cookies'
], function ($) {
    'use strict';

    return function (config) {
        let allowServices = false,
            allowedCookies,
            allowedWebsites,
            measurementId;

        if (config.isCookieRestrictionModeEnabled) {
            allowedCookies = $.mage.cookies.get(config.cookieName);

            if (allowedCookies !== null) {
                allowedWebsites = JSON.parse(allowedCookies);

                if (allowedWebsites[config.currentWebsite] === 1) {
                    allowServices = true;
                }
            }
        } else {
            allowServices = true;
        }

        if (!allowServices || !config.googleAnalyticsAvailable) {
            return;
        }

        measurementId = config.pageTrackingData.measurementId;

        if (!globalThis.gtag) {
            (function (d, s, u) {
                const gtagScript = d.createElement(s);

                gtagScript.type = 'text/javascript';
                gtagScript.async = true;
                gtagScript.src = u;
                d.head.insertBefore(gtagScript, d.head.children[0]);
            })(document, 'script', 'https://www.googletagmanager.com/gtag/js?id=' + measurementId);

            globalThis.dataLayer = globalThis.dataLayer || [];
            globalThis.gtag = function () {
                globalThis.dataLayer.push(arguments);
            };
            globalThis.gtag('js', new Date());
            globalThis.gtag('set', 'developer_id.dYjhlMD', true);
        }

        if (config.pageTrackingData.isAnonymizedIpActive) {
            globalThis.gtag('config', measurementId, {'anonymize_ip': true});
        } else {
            globalThis.gtag('config', measurementId);
        }

        if (config.ordersTrackingData.hasOwnProperty('currency')) {
            let purchaseObject = config.ordersTrackingData.orders[0];

            purchaseObject['items'] = config.ordersTrackingData.products;
            globalThis.gtag('event', 'purchase', purchaseObject);
        }

        if (config.productTrackingData.hasOwnProperty('currency') &&
            config.productTrackingData.action === 'catalog_category_view') {
            globalThis.gtag('event', 'view_item_list', {
                'item_list_id': config.productTrackingData.item_list_id,
                'item_list_name': config.productTrackingData.item_list_name,
                'items': config.productTrackingData.products
            });
        }
    };
});
