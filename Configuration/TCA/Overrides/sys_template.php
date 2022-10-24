<?php

defined('TYPO3') or die('Access denied.');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
    'formrelay_salesforce',
    'Configuration/TypoScript',
    'FormRelay Salesforce'
);
