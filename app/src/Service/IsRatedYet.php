<?php

namespace App\Service;

class IsRatedYet
{
    public function isRated($score)
    {
        if (!$score) {
            return false;
        }

        return true;
    }
}