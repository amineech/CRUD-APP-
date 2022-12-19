<?php

namespace App\Security\Voter;

use App\Entity\User;
use SplSubject;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;


class UserVoter extends Voter
{
    public const LIST = 'personnes_list';
    public const ADD = 'personnes_add';
    public const EDIT = 'personnes_edit';
    public const DELETE = 'personnes_delete';
    

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::LIST, self::ADD, self::EDIT, self::DELETE]) && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // check conditions and return true to grant permission
        if($user instanceof User) {
            if(!$user->isIsApproved() && !$subject->isIsApproved())
                return false;
            
            return true;
        }

        return false;
    }
}
