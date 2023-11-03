<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if ($user->getIsActive() === 0) {
// the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Your user account no longer exists.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

// user account is expired, the user may be notified
        if ($user->getIsActive() === 1) {
            throw new CustomUserMessageAccountStatusException('Your user account was banned');
//            throw new AccountExpiredException('Your user account was banned');
        }
    }
}