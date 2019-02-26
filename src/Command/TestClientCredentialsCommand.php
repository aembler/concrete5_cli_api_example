<?php
namespace Concrete\Console\Api\Example\Command;

use Concrete\Api\Client\OAuth2\Configuration\ClientCredentialsConfiguration;
use Concrete\Api\Client\OAuth2\Middleware\GrantType\ClientCredentialsGrantType;
use Concrete\Console\Api\Example\Console\ClientFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestClientCredentialsCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('test:client_credentials')

            // the short description shown while running "php bin/console list"
            ->setDescription('Queries a concrete5 site for its system information using the client credentials grant.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $this->populateConfiguration(new ClientCredentialsConfiguration(), $input, $output);
        $configuration->setScopes(['system']);

        $clientFactory = new ClientFactory(
            $configuration,
            new ClientCredentialsGrantType(),
            $output
        );

        $client = $clientFactory->createClient();
        $info = $client->system()->getSystemInformation();

        $output->writeln('<fg=blue>concrete5 System Information:</>');
        $output->writeln(sprintf('Version: %s', $info['data']['version']));
        $output->writeln(sprintf('PHP Version: %s', $info['data']['php_version']));
    }
}
