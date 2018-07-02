<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

// register Hook to process data
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['formrelay']['dataProcessor'][] = 'Mediatis\\FormrelaySalesforce\\Hooks\\SalesForce';

// Make Extension Manager variable available in Typoscript:
$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['formrelay_salesforce']);
if (isset($conf['enableCampaignNumber']) && $conf['enableCampaignNumber'] == 1) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants('plugin.tx_formrelay_salesforce.enableCampaignNumber = 1');
}
