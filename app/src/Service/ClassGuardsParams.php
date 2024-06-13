<?php

namespace App\Service;

class ClassGuardsParams
{
    const MAX_LENGTH = 40;
    const AVATAR_PATH = [
        '/avatars/avatar1.png',
        '/avatars/avatar2.png',
        '/avatars/avatar3.png',
        '/avatars/avatar4.png',
        '/avatars/avatar5.png',
        '/avatars/avatar6.png'
    ];

    public function __construct(
        public array $errors = []
    ) {
    }

    /**
     * @throws \Exception
     */
    public function guardAgainstInvalidParam($param): bool
    {
        if (!$param) {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException('Param can\'t be empty.', 400);
        } else {
            if ($param = '') {
                $this->errors[] = 'The param can\'t be empty';
                return false;
            }
        }
        return true;
    }

    public function guardAgainstMaxList($numList, $isPublic): bool
    {
        if (count($numList) == 5) {
            $listType = $isPublic ? 'public' : 'private';
            $this->errors[] = 'You have already created the maximum number of ' . $listType . ' lists. Try deleting some.';
            return false;
        }
        return true;
    }

    public function guardAgainstExistingList($list): bool
    {
        if (!$list) {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException('The list doesn\'t exist.', 400);
        }
        return true;
    }

    public function guardAgainstMaxGamesInList($listGames): bool
    {
        if (count($listGames) == 9) {
            $this->errors[] = 'Any list doesn\'t exists.';
            return false;
        }
        return true;
    }

    public function guardAgainstLongParam($param): bool
    {
        if (strlen($param) > self::MAX_LENGTH) {
            $this->errors[] = 'Maximum length is ' . self::MAX_LENGTH . ' characters.';
            return false;
        }
        return true;
    }

    public function guardAgainstExistingEmail($email): bool
    {
        if ($email) {
            $this->errors[] = 'Email already in use.';
            return false;
        }
        return true;
    }

    public function guardAgainstExistingNick($nick): bool
    {
        if ($nick) {
            $this->errors[] = 'Nick already in use.';
            return false;
        }
        return true;
    }

    public function guardAgainstNotExistingGame($game): bool
    {
        if (!$game) {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException('The game doesn\'t exist.', 400);
        }
        return true;
    }

    public function guardAgainstInvalidPhone($phone): bool
    {
        $pattern = '/^(?:\+[0-9]{1,3})?[0-9]{9}$/';

        if (!preg_match($pattern, $phone) && $phone != '') {
            $this->errors[] = 'The phone isn\'t correct.';
            return false;
        }
        return true;
    }

    public function guardAgainstInvalidMail($mail): bool
    {
        $pattern = '/^[\w.-]+@[a-zA-Z\d.-]+\.[a-zA-Z]{2,}$/';

        if (!preg_match($pattern, $mail)) {
            $this->errors[] = 'The mail isn\'t valid.';
            return false;
        }
        return true;
    }

    public function guardAgainstInvalidAvatar($avatarPath): bool
    {
        if (!in_array($avatarPath, self::AVATAR_PATH) && $avatarPath != null) {
            $this->errors[] = 'The avatar isn\'t valid.';
            return false;
        }
        return true;
    }

    public function guardAgainstInvalidRate($rate): bool
    {
        if ($rate < 1 || $rate > 10) {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException('The rate isn\'t valid.', 400);
        }
        return true;
    }

    public function guardAgainstInvalidId($entity): bool
    {
        if (empty($entity)) {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(400, 'The entered ID does not exist.');
        }
        return true;
    }
}
