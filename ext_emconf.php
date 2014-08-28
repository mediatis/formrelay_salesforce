<?php

########################################################################
# Extension Manager/Repository config file for ext: "leica_sfsend"
#
# Auto generated 13-05-2009 13:55
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Leica SalesForce send',
	'description' => 'Send form data to SalesForce via web2lead API',
	'category' => 'be',
	'author' => '',
	'author_email' => '',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'stable',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author_company' => '',
	'version' => '0.0.3',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'leica_sendform' => '0.0.2',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:10:{s:9:"ChangeLog";s:4:"453a";s:10:"README.txt";s:4:"ee2d";s:12:"ext_icon.gif";s:4:"0fab";s:17:"ext_localconf.php";s:4:"901a";s:14:"ext_tables.php";s:4:"31e0";s:19:"doc/wizard_form.dat";s:4:"5476";s:20:"doc/wizard_form.html";s:4:"1c7e";s:38:"hook/class.tx_leicasfsend_sendsf.php";s:4:"6e54";s:34:"static/leica_sfsend/constants.txt";s:4:"13f1";s:30:"static/leica_sfsend/setup.txt";s:4:"cabb";}',
	'suggests' => array(
	),
);

?>