<?php
namespace Concrete\Console\Api\Example\Console;

use Concrete\Nightcap\ClientFactory as ApiClientFactory;
use Concrete\Nightcap\OAuth2\Configuration\ClientCredentialsConfiguration;
use Concrete\Nightcap\OAuth2\Configuration\ConfigurationInterface;
use Concrete\Api\Client\OAuth2\Helper\ProviderBridge;
use Concrete\Nightcap\OAuth2\Middleware\Client\AuthorizationClientFactory;
use Concrete\Nightcap\OAuth2\Middleware\GrantType\ClientCredentialsGrantType;
use Concrete\Nightcap\OAuth2\Middleware\GrantType\GrantTypeInterface;
use Concrete\Nightcap\OAuth2\Middleware\MiddlewareFactory;
use Concrete\Api\Client\Service\Concrete5ServiceCollection;
use Concrete\Nightcap\Service\ServiceDescriptionFactory;
use Concrete\Nightcap\ServiceClientFactory;
use kamermans\OAuth2\Persistence\NullTokenPersistence;
use Monolog\Logger;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Symfony\Component\Console\Output\OutputInterface;

class ClientFactory
{

    /**
     * @var GrantTypeInterface
     */
    protected $grantType;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(
        ConfigurationInterface $configuration,
        GrantTypeInterface $grantType,
        OutputInterface $output)
    {
        $this->output = $output;
        $this->grantType = $grantType;
        $this->configuration = $configuration;
    }

    public function createClient()
    {
        // Now the League OAuth2 driver.
        $oauthProvider = ProviderBridge\Concrete5::createProvider($this->configuration);

        // Now the grant type object, which is separate from the grant type configuration object.
        $grantType = new ClientCredentialsGrantType();

        // The authorization client factory
        $authorizationClientFactory = new AuthorizationClientFactory($oauthProvider);

        // Our token persistence library. Could persist to a file, or to a caching layer, or write your own.
        // I'm going to omit one for now.
        $tokenPersistence = new NullTokenPersistence();

        // Now, our middleware factory object
        $middlewareFactory = new MiddlewareFactory(
            $this->configuration,
            $this->grantType,
            $authorizationClientFactory,
            $tokenPersistence
        );

        // Our web service client factory
        $serviceClientFactory = new ServiceClientFactory();

        // Our service description factory
        $serviceDescriptionFactory = new ServiceDescriptionFactory();

        // our service collection
        $serviceCollection = new Concrete5ServiceCollection();

        // Finally, let's log messages to the console if we're very verbose.
        $logger = null;
        if ($this->output->isVeryVerbose()) {
            $handler = new ConsoleHandler($this->output);
            $logger = new Logger($handler);
        }

        // Our client factory
        $clientFactory = new ApiClientFactory(
            $middlewareFactory,
            $serviceClientFactory,
            $serviceDescriptionFactory,
            $serviceCollection,
            $logger
        );

        // And finally our client.
        $client = $clientFactory->create();

        return $client;
    }
}
