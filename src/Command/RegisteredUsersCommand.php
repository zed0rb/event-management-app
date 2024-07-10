<?php

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:registered-users',
    description: 'List all registered users with their unique emails'
)]
class RegisteredUsersCommand extends Command
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $users = $this->userRepository->findAll();

        if (!$users) {
            $io->warning('No registered users found.');
            return Command::SUCCESS;
        }

        // Extract unique emails
        $uniqueEmails = [];
        foreach ($users as $user) {
            $email = $user->getEmail();
            if (!in_array($email, $uniqueEmails)) {
                $uniqueEmails[] = $email;
            }
        }

        // Output the unique emails
        $data = [];
        foreach ($uniqueEmails as $email) {
            $data[] = [$email];
        }

        $io->table(['Unique Emails'], $data);

        return Command::SUCCESS;
    }
}

