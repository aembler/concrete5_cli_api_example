<?php
namespace Concrete\Console\Api\Example\Command;

use Concrete\Api\Client\OAuth2\Configuration\ConfigurationInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

abstract class AbstractCommand extends Command
{

    protected function populateConfiguration(
        ConfigurationInterface $configuration,
        InputInterface $input,
        OutputInterface $output
    ) {
        $configurationFile = dirname(__DIR__, 2) . '/configuration.php';
        if (!file_exists($configurationFile)) {
            throw new \Exception('You must create a configuration.php file to hold your client ID and client Secret.');
        }

        $configurationParams = include($configurationFile);

        $helper = $this->getHelper('question');
        $question1 = new Question('URL of the concrete5 site: ', 'http://concrete5.test');
        $baseUrl = $helper->ask($input, $output, $question1);

        $clientId = $configurationParams['clientId'];
        $clientSecret = $configurationParams['clientSecret'];

        $configuration->setClientId($clientId);
        $configuration->setClientSecret($clientSecret);
        $configuration->setBaseUrl($baseUrl);
        return $configuration;
    }
}
