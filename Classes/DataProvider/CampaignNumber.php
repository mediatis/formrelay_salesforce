<?php

namespace Mediatis\FormrelaySalesforce\DataProvider;

use Mediatis\Formrelay\Configuration\ConfigurationManager;
use Mediatis\Formrelay\DataProvider\DataProviderInterface;

class CampaignNumber implements DataProviderInterface
{
    /** @var ConfigurationManager */
    protected $configurationManager;

    public function injectConfigurationManager(ConfigurationManager $configurationManager)
    {
        $this->configurationManager = $configurationManager;
    }

    public function addData(array &$dataArray)
    {
        if ($_COOKIE['sfCampaignNumber'] != "") {
            $dataArray['sf_campaign_number'] = $_COOKIE['sfCampaignNumber'];
            $settings = $this->configurationManager->getExtensionSettings('tx_formrelay_salesforce');
            if ($settings['campaignNumber.']['deleteCookieAfterSending'] == 1) {
                setcookie('sfCampaignNumber', '', time() - 3600, '/');
            }
        }
    }
}
