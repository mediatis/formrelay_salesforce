<?php

namespace Mediatis\FormrelaySalesforce\DataProvider;

use FormRelay\Core\DataProvider\DataProvider;
use FormRelay\Core\Model\Submission\SubmissionInterface;
use FormRelay\Core\Request\RequestInterface;

class SfdcCampaignNumberDataProvider extends DataProvider
{
    const KEY_COOKIE_NAME = 'cookieName';
    const DEFAULT_COOKIE_NAME = 'sfCampaignNumber';

    const KEY_FIELD = 'field';
    const DEFAULT_FIELD = 'sf_campaign_number';

    const KEY_DELETE_COOKIE_AFTER_SENDING = 'deleteCookieAfterSending';
    const DEFAULT_DELETE_COOKIE_AFTER_SENDING = false;

    protected function processContext(SubmissionInterface $submission, RequestInterface $request)
    {
        $cookieName = $this->getConfig(static::KEY_COOKIE_NAME);
        $exists = $this->addCookieToContext($submission, $request, $cookieName);
        if ($exists && $this->getConfig(static::KEY_DELETE_COOKIE_AFTER_SENDING)) {
            setcookie($cookieName, '', time() - 3600, '/');
        }
    }

    protected function process(SubmissionInterface $submission)
    {
        $cookieName = $this->getconfig(static::KEY_COOKIE_NAME);
        $campaignNumber = $this->getCookieFromContext($submission, $cookieName);
        if ($campaignNumber) {
            $field = $this->getConfig(static::KEY_FIELD);
            $this->setField($submission, $field, $campaignNumber);
        }
    }

    public static function getDefaultConfiguration(): array
    {
        return parent::getDefaultConfiguration() + [
            static::KEY_COOKIE_NAME => static::DEFAULT_COOKIE_NAME,
            static::KEY_FIELD => static::DEFAULT_FIELD,
            static::KEY_DELETE_COOKIE_AFTER_SENDING => static::DEFAULT_DELETE_COOKIE_AFTER_SENDING,
        ];
    }
}
