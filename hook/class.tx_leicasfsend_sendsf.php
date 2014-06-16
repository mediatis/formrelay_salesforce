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

	private $conf;

	function sendFormmail_preProcessVariables($EMAIL_VARS, &$obj){
		$this->conf = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_leicasfsend_sendsf.'];
		t3lib_div::debug($EMAIL_VARS, 'EMAIL_VARS');
		// t3lib_div::debug($obj, 'obj');
		t3lib_div::debug($GLOBALS['TSFE']->tmpl->setup, 'Alt2');
		
		$acceptedCountries = array(
			'Germany' => true,
			'United States ' => false 
			);


		// TODO: Check if country if allowed, otherwise don't do anything.
	

			if($EMAIL_VARS['Selectyourcountry:'] == $acceptedCountries & $permit){


				// TODO: Only send Sales-Request to sf

				// Do nothing, if plugin.tx_leicasfsend_sendsf.enabled is not set to true
				if($this->conf['enabled']) {


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


					$fieldData = array(
								'Inquiry_Type' => '00N20000007Lpgs',
								'Area_of_Interest' => '00N20000007Lpr7',
								'name' => '00N20000007LqJa',
								'How_can_we_help_you' => '00N20000007Lqu7',
								'CompanyInstitution' => 'company',
								'email' => 'email',
								'phone' => 'phone',
								'zip' => 'zip',
					);

					$result = array(
						'oid'=> '00D20000000n6sH',
						'submit' => 'Submit+Query',
						'retURL' => '#'
					);
					
					
					// Add FORM vars to XML
					foreach ($EMAIL_VARS as $key => $value) {
						// Ignore superfluous metadata
						if (!($key == 'html_enabled' ||
		                        $key == 'subject' ||
		                        $key == 'recipient' ||
		                        $key == 'recipient_copy')) {

							foreach ($fieldData as $formularFieldName => $salesForceFieldName) {
								if($key == $formularFieldName){
									$result[$salesForceFieldName] = $EMAIL_VARS[$formularFieldName];
								}else{
									$result[$key] = $value;
								}
							}

							
						}


					}
				}
			}

 			

			

			// TODO: remove recipient from $EMAIL_VARS, to prevent mail delivery

			// Log event
        	$this->writeToLogfile(sprintf('Sent mail "%s" FROM %s TO %s', $subject, $this->conf['from_mail'], $recipient));

			$this->sendToSalesforce($result);
			

			$EMAIL_VARS = $result;
		


		
		return $EMAIL_VARS;
	}

	function sendToSalesforce($data){
		$handle = curl_init();


		$query_string = '';

		foreach ($data as $key => $value) {
			$query_string = $query_string .'&'. $key .'=' . htmlspecialchars($value, ENT_QUOTES);
		}

		curl_setopt_array( $handle,
			array(
				CURLOPT_URL => 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8',
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => $query_string,
				)
			);

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
		$logfilePath = $this->conf['logfile_path'];

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