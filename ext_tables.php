<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
    'pages',
    'EXT:formrelay_salesforce/Resources/Private/Language/locallang_csh_pages.xlf'
);
