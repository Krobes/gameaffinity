<?php

/*
 * Descripción: En este servicio compruebo si un usuario ya ha votado un juego o no.
 *
 * Autor: Rafael Bonilla Lara
 */

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