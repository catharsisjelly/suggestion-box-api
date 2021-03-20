<?php

namespace App\Security\Voter;

use App\Entity\Suggestion;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SuggestionVoter extends Voter
{
    const SUGGESTION_CREATE = 'SUGGESTION_CREATE';
    const SUGGESTION_UPDATE = 'SUGGESTION_UPDATE';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::SUGGESTION_CREATE, self::SUGGESTION_UPDATE])
            && $subject instanceof Suggestion;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /** @var Suggestion $suggestion */
        $suggestion = $subject;
        $box = $suggestion->getBox();

        if (!$box->isOpen()) {
            return false;
        }

        // Does this box have no startDatetime and no endDatetime
        if ($box->getStartDatetime() === null && $box->getEndDatetime() === null) {
            return true;
        }

        // Does this box have a no startDatetime and an endDatetime
        if ($box->getStartDatetime() === null && $box->getEndDatetime() !== null) {
            return ($box->getEndDatetime() >= new \DateTimeImmutable());
        }

        // Does this box have a startDatetime and no endDatetime
        if ($box->getStartDatetime() !== null && $box->getEndDatetime() === null) {
            return ($box->getStartDatetime() <= new \DateTimeImmutable());
        }

        if ($box->getStartDatetime() !== null && $box->getEndDatetime() !== null) {
            return (
                ($box->getStartDatetime() <= new \DateTimeImmutable()) &&
                ($box->getEndDatetime() >= new \DateTimeImmutable())
            );
        }

        return false;
    }
}
