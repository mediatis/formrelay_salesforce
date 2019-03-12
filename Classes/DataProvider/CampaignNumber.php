<?php

namespace Mediatis\FormrelaySalesforce\DataProvider;

use Mediatis\Formrelay\Utility\FormrelayUtility;

class CampaignNumber implements \Mediatis\Formrelay\DataProviderInterface
{
    public function addData(&$dataArray)
    {
        if ($_COOKIE['sfCampaignNumber'] != "") {
            $dataArray['sf_campaign_number'] = $_COOKIE['sfCampaignNumber'];

            $settings = FormrelayUtility::loadPluginTS('tx_formrelay_salesforce');
            if ($settings['campaignNumber.']['deleteCookieAfterSending'] == 1) {
                setcookie('sfCampaignNumber', '', time() - 3600, '/');
            }
        }
    }
}
