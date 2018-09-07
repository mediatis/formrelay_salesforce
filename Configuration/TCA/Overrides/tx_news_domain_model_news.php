<?php
if (!defined('TYPO3_MODE')) {
  die ('Access denied.');
}

//** Global Extension Settings
$conf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['formrelay_salesforce']);

if (isset($conf['enableCampaignNumber']) && $conf['enableCampaignNumber'] == 1) {
    $fields = array(
        "tx_formrelaysalesforce_campaignnumber" => array(
            "exclude" => 1,
            "l10n_mode" => "exclude",
            "label" => "LLL:EXT:formrelay_salesforce/Resources/Private/Language/locallang_db.xml:tx_formrelaysalesforce_campaignnumber",
            "config" => array(
                'type' => 'input'
            )
        )
    );

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $fields);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
      'tx_news_domain_model_news',
      '--palette--;LLL:EXT:formrelay_salesforce/Resources/Private/Language/locallang_db.xml:tx_formrelaysalesforce_palette;tx_formrelaysalesforce_fields',
      '',
      'after:bodytext'
    );

    $GLOBALS['TCA']['tx_news_domain_model_news']['palettes']['tx_formrelaysalesforce_fields'] = array(
      'showitem' => 'tx_formrelaysalesforce_campaignnumber'
    );
}