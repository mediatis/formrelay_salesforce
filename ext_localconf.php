<?php
if (!defined ('TYPO3_MODE')) {
    die ('Access denied.');
}

// register Hook to process data
$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['formrelay']['dataProcessor'][] = 'Mediatis\\FormrelaySalesforce\\Hooks\\SalesForce';
