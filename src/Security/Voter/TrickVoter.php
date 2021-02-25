<?php

namespace App\Security\Voter;

use App\Entity\Trick;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TrickVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['EDIT', 'DELETE'])
            && $subject instanceof Trick;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case 'DELETE':
                if ($user === $subject->getUser()) {
                    return true;
                }
                break;
        }

        return false;
    }
}
