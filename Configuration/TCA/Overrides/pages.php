<?php

defined('TYPO3') or die();

//** Global Extension Settings
$conf = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('formrelay_salesforce');

if ($conf['enableCampaignNumber'] ?? false) {
    $fields = [
        'tx_formrelaysalesforce_campaignnumber' => [
            'exclude' => 1,
            'l10n_mode' => 'exclude',
            'label' => 'LLL:EXT:formrelay_salesforce/Resources/Private/Language/locallang_db.xlf:tx_formrelaysalesforce_campaignnumber',
            'config' => [
                'type' => 'input',
            ],
        ],
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $fields);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
        'pages',
        '--palette--;LLL:EXT:formrelay_salesforce/Resources/Private/Language/locallang_db.xlf:tx_formrelaysalesforce_palette;tx_formrelaysalesforce_fields',
        '',
        'after:nav_title'
    );

    $GLOBALS['TCA']['pages']['palettes']['tx_formrelaysalesforce_fields'] = [
        'showitem' => 'tx_formrelaysalesforce_campaignnumber',
    ];
}
