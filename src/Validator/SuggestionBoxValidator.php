<?php

namespace App\Validator;

use App\Entity\Suggestion;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SuggestionBoxValidator extends ConstraintValidator
{
    /**
     * @param $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Suggestion) {
            return;
        }

        $box = $value->getBox();

        if (!$box->isOpen()) {
            $this->context->buildViolation($constraint->isNotOpenMessage)
                ->addViolation();
        }

        $startDatetime = $box->getStartDatetime();
        $endDatetime = $box->getEndDatetime();

        // Does this box have no startDatetime and no endDatetime
        if (!$startDatetime && !$endDatetime) {
            return;
        }

        $now = new \DateTimeImmutable();
        // Has the timebox started?
        if ($startDatetime !== null && $startDatetime > $now) {
            $this->context->buildViolation($constraint->hasNotStartedMessage)
                ->setParameter(
                    '{{ value }}',
                    $this->formatValue($startDatetime, ConstraintValidator::PRETTY_DATE)
                )
                ->addViolation();
        }

        // Has the timebox ended?
        if ($endDatetime !== null && $endDatetime < $now) {
            $this->context->buildViolation($constraint->hasEndedMessage)
                ->setParameter(
                    '{{ value }}',
                    $this->formatValue($endDatetime, ConstraintValidator::PRETTY_DATE)
                )
                ->addViolation();
        }

    }
}
