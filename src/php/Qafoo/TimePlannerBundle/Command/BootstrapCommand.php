<?php

namespace Qafoo\TimePlannerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class BootstrapCommand extends ContainerAwareCommand
{
    /**
     * Default users
     *
     * @var array
     */
    protected $defaultUsers = array('manuel', 'kore', 'toby', 'benjamin');

    protected function configure()
    {
        $this
            ->setName('timeplanner:bootstrap')
            ->setDescription('Bootstrap aapplication with default users');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $userManipulator = $this->getContainer()->get('fos_user.util.user_manipulator');
        foreach ($this->defaultUsers as $user) {
            $output->writeln(" * Create user: $user; Password: password");
            $userManipulator->create($user, 'password', "$user@qafoo.com", true, false);
        }
    }
}
