<?php

namespace Mediatis\FormrelaySalesforce\Configuration;

use Mediatis\Formrelay\Configuration\RouteConfigurationUpdaterInterface;

class ConfigurationUpdater implements RouteConfigurationUpdaterInterface
{
    /**
     * @param array<mixed> $routeConfiguration
     */
    protected function updateUrlConfiguration(array &$routeConfiguration): void
    {
        if (array_key_exists('salesForceUrl', $routeConfiguration)) {
            if (!array_key_exists('url', $routeConfiguration)) {
                $routeConfiguration['url'] = $routeConfiguration['salesForceUrl'];
            }
            unset($routeConfiguration['salesForceUrl']);
        }
    }

    /**
     * @param array<mixed> $routeConfiguration
     */
    public function updateRouteConfiguration(string $routeName, array &$routeConfiguration): void
    {
        if ($routeName === 'salesforce') {
            $this->updateUrlConfiguration($routeConfiguration);
        }
    }
}
