<?php
if (!defined('TYPO3')) {
    die ('Access denied.');
}

(function() {
    // relay initalization
    \Mediatis\Formrelay\Utility\RegistrationUtility::registerInitialization(\Mediatis\FormrelaySalesforce\Initialization::class);

    // configuration updater
    \Mediatis\Formrelay\Utility\RegistrationUtility::registerRouteConfigurationUpdater(\Mediatis\FormrelaySalesforce\Configuration\ConfigurationUpdater::class);

    // setup enabled flag for data provider sfdcCampaignNumber
    $enableCampaignNumber = '0';
    $conf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)
        ->get('formrelay_salesforce');
    if (isset($conf['enableCampaignNumber']) && $conf['enableCampaignNumber'] === '1') {
        $enableCampaignNumber = '1';
    }
    // Make Extension Manager variable available in Typoscript:
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
        'plugin.tx_formrelay.settings.dataProviders.sfdcCampaignNumber.enabled = ' . $enableCampaignNumber
    );
})();
