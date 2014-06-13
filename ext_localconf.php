<?php
if (!defined ('TYPO3_MODE')) {
 	die ('Access denied.');
}

// Register Hook to preprocess Formmail Mail forms
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_fe.php']['sendFormmail-PreProcClass'][] = 'EXT:leica_sfsend/hook/class.tx_leicasfsend_sendsf.php:tx_leicasfsend_sendsf';
?>