<?php

namespace App\Services;

use App\Entity\Employe;
use Symfony\Bundle\SecurityBundle\Security;

class AccessControl{

    public function __construct(
        private Security $security,
    )
    {
        
    }
    public function controleAccesProjet ($employes, $currentUser) : bool    {
        if ($this->security->isGranted('ROLE_ADMIN')){
            return true;
        }
        foreach($employes as $employe) {
            if ($employe->getEmail() === $currentUser) {
                return true;
            }
        }
    return false;
    }
}
