<?php

namespace App\Utils;

use App\Entity\Employe;

class AccessControl{
    public function controleAccesProjet ($employes, $currentUser) : int    {
        $flag = 0;
        $employe = new Employe();
        foreach($employes as $employe) {
            if ($employe->getEmail() === $currentUser) {
                $flag = 1;
            }
        }
    return $flag;
    }

}
