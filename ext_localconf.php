<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// register Hook to process data
(function () {
    $registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Mediatis\Formrelay\Service\Registry::class);

    // register destination
    $registry->registerDestination(\Mediatis\FormrelaySalesforce\Destination\Salesforce::class);

    $conf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        'TYPO3\\CMS\\Core\\Configuration\\ExtensionConfiguration'
    )->get('formrelay_salesforce');
    if (
        isset($conf['enableCampaignNumber'])
        && $conf['enableCampaignNumber'] === '1'
    ) {
        // Make Extension Manager variable available in Typoscript:
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
            'plugin.tx_formrelay_salesforce.settings.enableCampaignNumber = 1'
        );

        // register data provider CampaignNumber
        $registry->registerDataProvider(\Mediatis\FormrelaySalesforce\DataProvider\CampaignNumber::class);
    }
})();

