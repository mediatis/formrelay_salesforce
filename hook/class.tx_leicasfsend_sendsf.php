<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Michael Vöhringer (mediatis AG) <voehringer@mediatis.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/



/**
 * Plugin Send form data to SourceFoce.com
 *
 * @author	Michael Vöhringer (mediatis AG) <voehringer@mediatis.de>
 * @package	TYPO3
 * @subpackage	leica_sfsend
 */
class tx_leicasfsend_sendsf {


	private $tsConf;
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->tsConf  = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_leicasfsend_sendsf.'];
        if(!$this->tsConf ) {
            $tsSetup = $this->loadTS($GLOBALS['TSFE']->id);
            $this->tsConf  = $tsSetup['plugin.']['tx_leicasfsend_sendsf.'];
        }

	}


    /**
     * Get TypoScript configuration for a specific page
     *
     * @param integer $pageId: Page id
     * @return array: TS Setup
     */
    function loadTS($pageId) {
        $pS = t3lib_div::makeInstance('t3lib_pageSelect');
        tslib_fe::includeTCA();
        $TSObj = t3lib_div::makeInstance('t3lib_TStemplate');
        $TSObj->tt_track = 0;
        $TSObj->init();
        $TSObj->start($pS->getRootline((int)$pageId));
        return $TSObj->setup;
    }

    private function validateForm($mailVars) {

    	foreach ($this->tsConf['fields.']['validation.']['values.'] as $key => $value) {
    		if (!isset($mailVars[$key])) {
    			return false;
    		}

    		$possibleValues = explode(',', strtolower($value));
    		$currentValue = strtolower($mailVars[$key]);
    		if (!in_array($currentValue, $possibleValues)) {
    			return false;
    		}
    	}

    	if (trim($this->tsConf['fields.']['validation.']['required.']) !== '') {
    		$requiredValues = explode(',', trim($this->tsConf['fields.']['validation.']['required.']));
	    	foreach ($requiredValues as $requiredValue) {
	    		if (!isset($mailVars[$requiredValue])) {
	    			return false;
	    		}
	    	}
	    }
    	return true;
    }

    private function processField(&$result, $key, $mappedValue, $mappedKey) {

    	$keyPrefixIndex = strpos($mappedKey, ':');
		$keyPrefix = false;
		if ($keyPrefixIndex !== FALSE && $keyPrefixIndex > 0) {
			$keyPrefix = substr($mappedKey, 0, $keyPrefixIndex);
			$mappedKey = substr($mappedKey, $keyPrefixIndex + 1);
		}

		switch ($keyPrefix) {
			case 'split':
				// explode the value using the space char as separator, split the result to the given fields
				// example:
				// mapping = 'split:first_name,last_name'
				// value = 'John Doe' => result = array('first_name' => 'John', 'last_name' => 'Doe');
				// value = 'John' => result = array('first_name' => 'John');
				// value = 'John Doe Smith' => result = array('first_name' => 'John', 'last_name' => 'Doe Smith');
				$valueSeparator = ' ';
				$splitToFields = explode(',', $mappedKey);
				$splittedValues = explode($valueSeparator, $mappedValue);
				while (count($splitToFields) > 1 && count($splittedValues) > 0) {
					// split for all fields but the last
					$splittedField = array_shift($splitToFields);
					$splittedValue = array_shift($splittedValues);
					$this->processField($result, $key, $splittedValue, $splittedField);
				}
				if (count($splittedValues) > 0) {
					// concat the remaining splitted values again and use them for the last field
					$splittedField = array_shift($splitToFields);
					$splittedValue = implode($valueSeparator, $splittedValues);
					$this->processField($result, $key, $splittedValue, $splittedField);
				}
			break;

			case 'fields':
				// share the value with multiple fields
				// example:
				// mapping = 'fields:country,country_code'
				// value = 'US' => result = array('country' => 'US', 'country_code' => 'US');
				$sharedKeys = explode(',', $mappedKey);
				foreach ($sharedKeys as $sharedKey) {
					$this->processField($result, $key, $mappedValue, $sharedKey);
				}
			break;

			case 'concat':
				// concat the key-value-pair into one field (along with other pairs)
				// example:
				// mapping = 'concat:description'
				// key = 'foo'; value = 'bar'
				// followed by key = 'oof'; value = 'baz'
				// result = array('description' => 'foo = bar
				// oof = baz');
				if (!isset($result[$mappedKey])) { $result[$mappedKey] = ''; }
				$result[$mappedKey] .= $key . ' = ' . $mappedValue . PHP_EOL;
			break;

			default:
				// just use the key and value as key and value
				$result[$mappedKey] = $mappedValue;
			break;
		}
    }

	public function sendFormmail_preProcessVariables($EMAIL_VARS, &$obj){

		//t3lib_div::devLog('tx_leicasfsend_sendsf::sendFormmail_preProcessVariables', 'leica_sfsend');

		// Do nothing, if plugin.tx_leicasfsend_sendsf.enabled is not set to true
		if (!$this->tsConf['enabled']) {
			return $EMAIL_VARS;
		}

		if (!$this->validateForm($EMAIL_VARS)) {
			return $EMAIL_VARS;
		}

		// File upload Path
		// -> uploads/tx_leicasfsend/[year]/[month]/[day]/
		$filePath = 'uploads/tx_leicasfsend/' . date('Y/m/d/');
		if(!is_dir($filePath)){
			mkdir($filePath, 0755, true);
		}

		// Move uploaded Files from temp-Directory to /uploads/tx_leicasfsend
		// and add a link to the XML
		$fileCounter = 0;
		foreach($_FILES as $key => $file) {

			if($file['error'] == 0 && $file['size'] > 0) {

				// Generate new file name
				// -> [hash].[extension]
				$fileExt = strstr($file['name'], '.') ? strstr($file['name'], '.') : '';
				$fileName = $this->unique_filename($fileExt);

				// Move temp-file to upload directory
				$destination =  getcwd() . '/'. $filePath . $fileName; // Absolute Path
				if(!move_uploaded_file($file['tmp_name'], $destination)) {
					$this->writeToLogfile(sprintf('Error trying to move uploaded file "%s" to %s', $file['tmp_name'], $destination));
				}

				// Add link to uploaded file to EMAIL_VARS / XML
				$EMAIL_VARS[$key] = t3lib_div::getIndpEnv('TYPO3_SITE_URL') . $filePath . $fileName; // URL
				$fileCounter++;
			}
		}


		// create salesforce data

		$result = $this->tsConf['fields.']['defaults.'];

		$metaDataKeys = explode(',', trim(strtolower($this->tsConf['fields.']['ignore'])));
		foreach ($EMAIL_VARS as $key => $value) {

			// ignore empty values (mostly hidden fields)
			if (!trim($value)) { continue; }

			// ignore superfluous metadata
			if (in_array($key, $metaDataKeys)) { continue; }

			// if a value mapping exists, use it
			$mappedValue = $value;
			if (isset($this->tsConf['fields.']['values.']['mapping.'][$key . '.'][$value])) {
				$mappedValue = $this->tsConf['fields.']['values.']['mapping.'][$key . '.'][$value];
			}

			// if there is no mapping for the key, use the other-key
			$mappedKey = isset($this->tsConf['fields.']['mapping.'][$key]) ? $this->tsConf['fields.']['mapping.'][$key] : $this->tsConf['fields.']['mappingOther'];

			$this->processField($result, $key, $mappedValue, $mappedKey);
		}

		// Log event
    	$this->writeToLogfile(sprintf('Sent lead to SalesForce for "%s"', $EMAIL_VARS['email']));

		$this->sendToSalesforce($result);


		return false;
	}

	function sendToSalesforce($data){

		$params = array();
		foreach ($data as $key => $value) {
			$params[] = rawurlencode($key) . '=' . rawurlencode($value);
			//$queryString .= '&'. $key .'=' . htmlspecialchars($value, ENT_QUOTES);
		}
		$queryString = implode('&', $params);

		$curlOptions = array(
			CURLOPT_URL => $this->tsConf['salesForceUrl'],
			CURLOPT_POST => TRUE,
			CURLOPT_POSTFIELDS => $queryString,

			CURLOPT_REFERER => $_SERVER['HTTP_REFERER'],
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_FOLLOWLOCATION => TRUE,
			CURLOPT_MAXREDIRS => 10,
		);

		// t3lib_div::devLog('tx_leicaxmlsend_sendxml::sendToSalesforce', 'leica_sfsend');
		// t3lib_div::devLog('arguments: ' . print_r(func_get_args(), true), 'leica_sfsend');
		// t3lib_div::devLog('curl options: ' . print_r($curlOptions, true), 'leica_sfsend');

		$handle = curl_init();

		curl_setopt_array($handle, $curlOptions);

		curl_exec($handle);
		curl_close($handle);
	}


	// Generate filename from IP microtime and Extension. Low chance of not being unique
	function unique_filename($xtn = ".tmp") {
		// explode the IP of the remote client into four parts
		$ipbits = explode(".", t3lib_div::getIndpEnv('REMOTE_ADDR'));
		// Get both seconds and microseconds parts of the time
		list($usec, $sec) = explode(" ",microtime());

		// Fudge the time we just got to create two 16 bit words
		$usec = (integer) ($usec * 65536);
		$sec = ((integer) $sec) & 0xFFFF;

		// Convert the remote client's IP into a 32 bit hex number then tag on the time.
		$uid = sprintf("%08x%04x%04x",($ipbits[0] << 24)
			| ($ipbits[1] << 16)
			| ($ipbits[2] << 8)
			| $ipbits[3], $sec, $usec);

		// Tag on the extension and return the filename
		return $uid.$xtn;
	}

	// Write $data to logfile if $logfilepath is set in TS Config
	function writeToLogfile($logtext) {
		$logfilePath = $this->tsConf['logfile_path'];

		// Only write a logfile if path is set in TS Config and logtext is not empty
		if(strlen($logtext) > 0 && strlen($logfilePath) > 0) {

			// open logfile and place cursor at the end of file
			if($logfile = @fopen($logfilePath, "a")) {
				// write xml to logfile and close it
				@fwrite($logfile, $logtext);
				fclose($logfile);
			} else {
				debug("Could not open file at ". $logfilePath);
			}
		}
	}
}




if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/leica_sfsend/hook/class.tx_leicasfsend_sendsf.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/leica_sfsend/hook/class.tx_leicasfsend_sendsf.php']);
}

?>