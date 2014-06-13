<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}
// Add static file to list of all static files.
t3lib_extMgm::addStaticFile($_EXTKEY,'static/leica_sfsend/', 'Leica SourceForce send');
?>