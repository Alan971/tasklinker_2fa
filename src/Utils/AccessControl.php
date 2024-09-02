<?php

namespace App\Utils;

use App\Entity\Employe;

class AccessControl{
    public function controleAccesProjet ($employes, $currentUser) : bool    {
        foreach($employes as $employe) {
            if ($employe->getEmail() === $currentUser) {
                return true;
            }
        }
    return false;
    }
}
