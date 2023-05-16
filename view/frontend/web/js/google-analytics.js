/**
 * Copyright © Samdoit (support@samdoit.com). All rights reserved.
 * Please visit Samdoit.com for license details (https://samdoit.com/end-user-license-agreement).
 */
/* jscs:disable */
/* eslint-disable */
define([
    'jquery',
    'mage/cookies'
], function ($) {
    'use strict';

    /**
     * @param {Object} config
     */
    return function (config) {
        var allowServices = false,
            allowedCookies,
            allowedWebsites;

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

            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            // Process page info
            gtag('config', config.pageTrackingData.accountId);

            if (config.pageTrackingData.isAnonymizedIpActive) {
                gtag('config', config.pageTrackingData.accountId, { 'anonymize_ip': true });
            }
        }
    }
});
