<?php

namespace App\Service\User;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class AuthService
{
    public function __construct(
        private readonly Security $security,
        private readonly RequestStack $requestStack,
    ){
    }
    public function getUser(): ?UserInterface
    {
        return $this->security->getUser();
    }
    public function giveSomeInfo(): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $firewallName = $this->security->getFirewallConfig($request)?->getName();

        // ...
    }
}