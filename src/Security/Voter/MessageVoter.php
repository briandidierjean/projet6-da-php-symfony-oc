<?php


namespace App\Security\Voter;


use App\Entity\Message;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['DELETE'])
            && $subject instanceof Message;
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
            case 'DELETE':
                if ($user === $subject->getUser()) {
                    return true;
                }
                break;
        }

        return false;
    }
}