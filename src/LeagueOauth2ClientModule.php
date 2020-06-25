<?php
namespace Ptyhard\LeagueOauth2ClientModule;

use Ray\Di\AbstractModule;

class LeagueOauth2ClientModule extends AbstractModule
{
    /**
     * @var array
     */
    private $clients;

    /**
     * LeagueOauth2ClientModule constructor.
     *
     * @param array $clients
     */
    public function __construct(array $clients)
    {
        $this->clients = $clients;
        parent::__construct();
    }

    protected function configure() : void
    {
        foreach ($this->clients as $service => $client) {
            $providerClass = $client['provider'];
            $service = ! is_numeric($service)
                ? $service
                : strtolower(
                    substr($providerClass, strrpos($providerClass, '\\') + 1)
                );

            if (false === class_exists($providerClass)) {
                throw new ProviderException(
                    sprintf(
                        'Class %s not found. please install to  league/%s',
                        $providerClass,
                        $service
                    )
                );
            }

            $parameters = [
                'clientId' => $client['clientId'],
                'clientSecret' => $client['clientSecret'],
                'redirectUri' => $client['redirectUri']
            ];

            if (
                isset($client['extraAuthParams']) &&
                is_array($client['extraAuthParams'])
            ) {
                $parameters += $client['extraAuthParams'];
            }

            $optionName = "oauth_client_{$service}_options";

            $this->bind($providerClass)
                ->annotatedWith("oauth_client_{$service}")
                ->toConstructor($providerClass, [
                    'options' => $optionName
                ]);

            $this->bind()
                ->annotatedWith($optionName)
                ->toInstance($parameters);
        }
    }
}
