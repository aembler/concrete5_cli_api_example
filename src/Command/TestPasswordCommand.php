<?php
namespace Concrete\Console\Api\Example\Command;

use Concrete\Nightcap\OAuth2\Configuration\PasswordCredentialsConfiguration;
use Concrete\Nightcap\OAuth2\Middleware\GrantType\PasswordCredentialsGrantType;
use Concrete\Console\Api\Example\Console\ClientFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class TestPasswordCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('test:password')

            // the short description shown while running "php bin/console list"
            ->setDescription('Queries a concrete5 site for its site trees using the password grant.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configuration = $this->populateConfiguration(new PasswordCredentialsConfiguration(), $input, $output);
        $configuration->setScopes(['system', 'site']);
        $helper = $this->getHelper('question');
        $questionUsername = new Question('concrete5 Username: ');
        $questionPassword = new Question('concrete5 Password: ');
        $username = $helper->ask($input, $output, $questionUsername);
        $password = $helper->ask($input, $output, $questionPassword);

        $configuration->setUsername($username);
        $configuration->setPassword($password);

        $clientFactory = new ClientFactory(
            $configuration,
            new PasswordCredentialsGrantType(),
            $output
        );

        $client = $clientFactory->createClient();
        $info = $client->site()->getSiteTrees();

        $output->writeln('<fg=blue>concrete5 Site Trees:</>');
        foreach($info['data'] as $tree) {
            $output->writeln(sprintf('Name: %s (ID: %s)', $tree['name'], $tree['id']));
        }
    }
}
