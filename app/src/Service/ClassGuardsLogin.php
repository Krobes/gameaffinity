<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ClassGuardsLogin
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        public array $errors = []
    ) {
    }

    /**
     * @throws \Exception
     */
    public function guardAgainstExistingNickorEmail($nick, $email)
    {
        $nickExist = $this->entityManager->getRepository(User::class)->findBy([
            'nick' => $nick
        ]);

        $emailExist = $this->entityManager->getRepository(User::class)->findBy([
            'email' => $email
        ]);

        if (!empty($emailExist)) {
            $this->errors[] = 'El email o nick introducido ya existe';
            return false;
        }

        if (!empty($nickExist)) {
            $this->errors[] = 'El email o nick introducido ya existe';
            return false;
        }
        return true;
    }

    public function guardAgainstWeakPassword($password)
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@\W@', $password);

        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 10) {
            $this->errors[] = 'La contraseña no es lo suficientemente segura';
            return false;
        }

        return true;
    }

    public function guardAgainstDifferentPasswords($firstPassword, $secondPassword)
    {
        if ($firstPassword != $secondPassword) {
            $this->errors[] = 'Las contraseñas no coinciden';
            return false;
        }
        return true;
    }

}