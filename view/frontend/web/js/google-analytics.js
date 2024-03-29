/**
 * Copyright © Samdoit (support@samdoit.com). All rights reserved.
 * Please visit Samdoit.com for license details (http://www.samdoit.com/end-user-license-agreement).
 */
/* jscs:disable */
/* eslint-disable */
define(
    [
    'jquery',
    'mage/cookies'
    ], function ($) {
        'use strict';

        /**
         * @param {Object} config
         */
        return function (config) {
            console.log('Manickam');
            console.log('google-analytics');
            console.log(JSON.stringify(config));
            var allowServices = false,
            allowedCookies,
            allowedWebsites,
            measurementId;

            if (config.isCookieRestrictionModeEnabled) {
                console.log('isCookieRestrictionModeEnabled');
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

            if (allowServices) {
                /* Global site tag (gtag.js) - Google Analytics */
                measurementId = config.pageTrackingData.measurementId;
                if (window.gtag) {
                    if (config.pageTrackingData.isAnonymizedIpActive) {
                        gtag('config', config.pageTrackingData.accountId, { 'anonymize_ip': true });
                    } else {
                        gtag('config', measurementId);

                    }
                    // Purchase Event
                    if (config.ordersTrackingData.hasOwnProperty('currency')) {
                        var purchaseObject = config.ordersTrackingData.orders[0];
                        purchaseObject['items'] = config.ordersTrackingData.products;
                        gtag('event', 'purchase', purchaseObject);
                    }
                } else {
                    (function (d,s,u) {
                        var gtagScript = d.createElement(s);
                        gtagScript.type = 'text/javascript';
                        gtagScript.async = true;
                        gtagScript.src = u;
                        d.head.insertBefore(gtagScript, d.head.children[0]);
                    })(document, 'script', 'https://www.googletagmanager.com/gtag/js?id=' + measurementId);
                    window.dataLayer = window.dataLayer || [];
                    function gtag()
                    {
                        dataLayer.push(arguments);}
                    gtag('js', new Date());
                    gtag('set', 'developer_id.dYjhlMD', true);
                    if (config.pageTrackingData.isAnonymizedIpActive) {
                        gtag('config', config.pageTrackingData.accountId, { 'anonymize_ip': true });
                    } else {
                        gtag('config', measurementId);

                    }
                    // Purchase Event
                    if (config.ordersTrackingData.hasOwnProperty('currency')) {
                        var purchaseObject = config.ordersTrackingData.orders[0];
                        purchaseObject['items'] = config.ordersTrackingData.products;
                        gtag('event', 'purchase', purchaseObject);
                    }
                }
            }
        }
    }
);
