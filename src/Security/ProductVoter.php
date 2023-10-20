<?php

namespace App\Security;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    public function __construct(
        private readonly Security $security,
    ) {
    }

    CONST DELETE = 'delete';
    CONST EDIT = 'edit';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Product) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /** @var Product $product */
        $product = $subject;

        return match($attribute) {
            self::DELETE => $this->canDelete(),
            self::EDIT => $this->canEdit($product, $user),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function canDelete(): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
        return false;
    }

    private function canEdit(Product $product, User $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN') || $user === $product->getUser()) {
            return true;
        }
        return false;
    }
}