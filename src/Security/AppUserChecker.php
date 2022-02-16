<?php

namespace App\Security;

use App\Entity\User;
use App\Security\Exceptions\CompteSuspenduException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AppUserChecker implements UserCheckerInterface
{

    /**
     * @inheritDoc
     */
    public function checkPreAuth(UserInterface $user)
    {
        if(!$user instanceof User) {
            return;
        }

        if(empty($user->getIsActive())) {
            $ex = new CompteSuspenduException('Ce compte est desactivé !');
            $ex->setUser($user);

            throw $ex;
        }
    }

    /**
     * @inheritDoc
     */
    public function checkPostAuth(UserInterface $user)
    {
        // TODO: Implement checkPostAuth() method.
    }
}