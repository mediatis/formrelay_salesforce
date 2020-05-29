<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// register Hook to process data
(function () {
    $registry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\Mediatis\Formrelay\Service\Registry::class);

    // register destination
    $registry->registerDestination(\Mediatis\FormrelaySalesforce\Destination\Salesforce::class);

    $conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['formrelay_salesforce']);
    if (isset($conf['enableCampaignNumber']) && $conf['enableCampaignNumber'] == 1) {

        // Make Extension Manager variable available in Typoscript:
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
            'plugin.tx_formrelay_salesforce.enableCampaignNumber = 1'
        );

        // register data provider CampaignNumber
        $registry->registerDataProvider(\Mediatis\FormrelaySalesforce\DataProvider\CampaignNumber::class);
    }
})();

