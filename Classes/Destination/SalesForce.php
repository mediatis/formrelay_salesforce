<?php

namespace Mediatis\FormrelaySalesforce\Destination;

use Mediatis\Formrelay\Destination\AbstractDestination;
use Mediatis\Formrelay\DataDispatcher\RequestDispatcher;

class SalesForce extends AbstractDestination
{
    public function getExtensionKey(): string
    {
        return "tx_formrelay_salesforce";
    }

    protected function getDispatcher(array $conf, array $data, array $context)
    {
        $salesForceUrl = $conf['salesForceUrl'];
        return $this->objectManager->get(RequestDispatcher::class, $salesForceUrl);
    }
}
