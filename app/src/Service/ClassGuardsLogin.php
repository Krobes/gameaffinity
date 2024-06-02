<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ClassGuardsLogin
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        public array $errors = []
    ) {
    }

    /**
     * @throws Exception
     */
    public function guardAgainstExistingNickOrEmail($nick, $email): bool
    {
        $nickExist = $this->entityManager->getRepository(User::class)->findBy([
            'nick' => $nick
        ]);

        $emailExist = $this->entityManager->getRepository(User::class)->findBy([
            'email' => $email
        ]);

        if (!empty($emailExist)) {
            $this->errors[] = 'The email or nick already exists';
            return false;
        }

        if (!empty($nickExist)) {
            $this->errors[] = 'The email or nick already exists';
            return false;
        }
        return true;
    }

    public function guardAgainstWeakPassword($password): bool
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@\W@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
            $this->errors[] = 'The password is not strong enough. It must contain at least one uppercase letter, one lowercase letter, one symbol, and be at least 8 characters long';
            return false;
        }

        return true;
    }

    public function guardAgainstDifferentPasswords($firstPassword, $secondPassword): bool
    {
        if ($firstPassword != $secondPassword) {
            $this->errors[] = 'Passwords don\'t match';
            return false;
        }
        return true;
    }

}