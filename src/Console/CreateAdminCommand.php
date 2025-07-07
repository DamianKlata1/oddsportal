<?php

namespace App\Console;

use App\Entity\User;
use App\Console\Trait\CommandExecutionTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use App\Repository\Interface\UserRepositoryInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\Interface\CommandLog\CommandLoggerServiceInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a new admin user.',
)]
class CreateAdminCommand extends Command
{
    use CommandExecutionTrait;
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly UserRepositoryInterface $userRepository,
        private readonly CommandLoggerServiceInterface $logger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $commandName = $this->getName();
        $this->logger->logStart($commandName);

        try {
            $email = $this->askForEmail($io, $input, $output);
            if ($this->userRepository->findOneBy(['email' => $email])) {
                return $this->handleFailure($io, $commandName, "User with email '$email' already exists.");
            }

            $password = $this->askForPassword($io, $input, $output);
            if (strlen($password) < 6) {
                return $this->handleFailure($io, $commandName, 'Password must be at least 6 characters long.');
            }

            $user = $this->createAdminUser($email, $password);
            $this->userRepository->save($user, true);

            $message = "Admin user '$email' successfully created!";
            return $this->handleSuccess($io, $commandName, $message);
        } catch (\Throwable $e) {
            return $this->handleFailure($io, $commandName, $e->getMessage());
        }
    }
    private function askForEmail(SymfonyStyle $io, InputInterface $input, OutputInterface $output): string
    {
        $question = new Question('Please enter the admin email: ');
        return $this->getHelper('question')->ask($input, $output, $question);
    }

    private function askForPassword(SymfonyStyle $io, InputInterface $input, OutputInterface $output): string
    {
        $question = new Question('Please enter the password: ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        return $this->getHelper('question')->ask($input, $output, $question);
    }

    private function createAdminUser(string $email, string $plainPassword): User
    {
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $plainPassword)
        );
        return $user;
    }
}