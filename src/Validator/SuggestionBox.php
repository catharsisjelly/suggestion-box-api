<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SuggestionBox extends Constraint
{
    public $isNotOpenMessage = 'The Suggestion Box is not open.';
    public $hasNotStartedMessage = 'The Suggestion Box has not started yet, due to start at {{ value }}.';
    public $hasEndedMessage = 'The Suggestion Box stopped taking suggestions at {{ value }}.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
