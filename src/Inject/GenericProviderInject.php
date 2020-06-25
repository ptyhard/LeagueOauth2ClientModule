<?php
namespace Ptyhard\LeagueOauth2ClientModule\Inject;

use League\OAuth2\Client\Provider\GenericProvider;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;

trait GenericProviderInject
{
    /**
     * @var GenericProvider
     */
    private $provider;

    /**
     * @Inject()
     * @Named("oauth_client_genericprovider")
     */
    public function setProvider(GenericProvider $provider) : void
    {
        $this->provider = $provider;
    }
}
