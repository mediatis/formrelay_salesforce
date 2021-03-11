<?php

namespace Mediatis\FormrelaySalesforce;

use FormRelay\Core\Service\RegistryInterface;
use FormRelay\Request\Route\RequestRoute;
use FormRelay\Salesforce\SalesforceInitialization;
use Mediatis\FormrelaySalesforce\DataProvider\SfdcCampaignNumberDataProvider;

class Initialization
{
    public function initialize(RegistryInterface $registry)
    {
        SalesforceInitialization::initialize($registry);
        $registry->deleteRoute(RequestRoute::class);

        $registry->registerDataProvider(SfdcCampaignNumberDataProvider::class);
    }
}
