<?php
namespace Mediatis\FormrelaySalesforce\DataProvider;

class CampaignNumber implements \Mediatis\Formrelay\DataProviderInterface
{
    public function addData(&$dataArray)
    {
        if ($_COOKIE['sfCampaignNumber'] != "") {
            $dataArray['sf_campaign_number'] = $_COOKIE['sfCampaignNumber'];
        }
    }
}
