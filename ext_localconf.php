<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// register Hook to process data
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['formrelay']['dataProcessor'][] = Mediatis\FormrelaySalesforce\Hooks\SalesForce::class;

$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['formrelay_salesforce']);
if (isset($conf['enableCampaignNumber']) && $conf['enableCampaignNumber'] == 1) {

    // Make Extension Manager variable available in Typoscript:
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
        'plugin.tx_formrelay_salesforce.enableCampaignNumber = 1'
    );

    // Add DataProvider for CampaignNumber
    $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['formrelay']['dataProvider'][] = Mediatis\FormrelaySalesforce\DataProvider\CampaignNumber::class;
}
