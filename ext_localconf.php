<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

(function() {
    /** @var \TYPO3\CMS\Extbase\SignalSlot\Dispatcher $dispatcher */
    $dispatcher = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Extbase\SignalSlot\Dispatcher::class);

    // relay initalization
    $dispatcher->connect(
        \FormRelay\Core\Service\RegistryInterface::class,
        \Mediatis\Formrelay\Factory\RegistryFactory::SIGNAL_UPDATE_REGISTRY,
        \Mediatis\FormrelayPardot\Initialization::class,
        'initialize'
    );

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

    // configuration updater
    $dispatcher->connect(
        \Mediatis\Formrelay\Configuration\RouteConfigurationUpdaterInterface::class,
        \Mediatis\Formrelay\Configuration\RouteConfigurationUpdaterInterface::SIGNAL_UPDATE_ROUTE_CONFIGURATION,
        \Mediatis\FormrelaySalesforce\Configuration\ConfigurationUpdater::class,
        'updateRouteConfiguration'
    );
})();
