<?php

namespace Mediatis\FormrelaySalesforce;

use FormRelay\Core\Service\RegistryInterface;
use FormRelay\Salesforce\SalesforceInitialization;
use Mediatis\FormrelaySalesforce\DataProvider\SfdcCampaignNumberDataProvider;

class Initialization
{
    public function initialize(RegistryInterface $registry): void
    {
        SalesforceInitialization::initialize($registry);
        $registry->registerDataProvider(SfdcCampaignNumberDataProvider::class);
    }
}
