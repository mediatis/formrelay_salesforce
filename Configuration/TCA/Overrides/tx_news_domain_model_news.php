<?php

defined('TYPO3') or die();

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('news')) {
    //** Global Extension Settings
    $conf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('formrelay_salesforce');

    if (isset($conf['enableCampaignNumber']) && $conf['enableCampaignNumber'] == 1) {
        $fields = [
            'tx_formrelaysalesforce_campaignnumber' => [
                'exclude' => 1,
                'l10n_mode' => 'exclude',
                'label' => 'LLL:EXT:formrelay_salesforce/Resources/Private/Language/locallang_db.xml:tx_formrelaysalesforce_campaignnumber',
                'config' => [
                    'type' => 'input',
                ],
            ],
        ];

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_news_domain_model_news', $fields);
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
            'tx_news_domain_model_news',
            '--palette--;LLL:EXT:formrelay_salesforce/Resources/Private/Language/locallang_db.xml:tx_formrelaysalesforce_palette;tx_formrelaysalesforce_fields',
            '',
            'after:bodytext'
        );

        $GLOBALS['TCA']['tx_news_domain_model_news']['palettes']['tx_formrelaysalesforce_fields'] = [
            'showitem' => 'tx_formrelaysalesforce_campaignnumber',
        ];
    }
}
