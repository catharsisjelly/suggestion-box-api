<?php

namespace App\Tests\Unit\Validator;

use App\Entity\Box;
use App\Entity\Suggestion;
use App\Validator\SuggestionBox;
use App\Validator\SuggestionBoxValidator;
use Mockery as m;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class SuggestionBoxValidatorTest extends ConstraintValidatorTestCase
{
    public function test_is_not_suggestion()
    {
        $box = m::mock(Box::class);

        $this->validator->validate($box, new SuggestionBox());
        $this->assertNoViolation();
    }

    /**
     * @dataProvider boxDataProvider
     * @param bool $isOpen
     * @param string|null $startDatetime
     * @param string|null $endDateTime
     * @param int $numberOfViolationsExpected
     * @param array $violations
     * @throws \Exception
     */
    public function test_is_open_false(
        bool $isOpen,
        ?string $startDatetime,
        ?string $endDateTime,
        int $numberOfViolationsExpected,
        array $violations
    ) {
        $box = m::mock(Box::class);
        $box->shouldReceive('isOpen')
            ->andReturn($isOpen);
        $box->shouldReceive('getStartDatetime')
            ->andReturn($startDatetime ? new \DateTimeImmutable($startDatetime) : null);
        $box->shouldReceive('getEndDatetime')
            ->andReturn($endDateTime ? new \DateTimeImmutable($endDateTime) : null);

        $suggestion = m::mock(Suggestion::class);
        $suggestion->shouldReceive('getBox')
            ->andReturn($box);

        $constraint = new SuggestionBox();
        $this->validator->validate($suggestion, $constraint);
        $returnedViolations = $this->context->getViolations();

        if ($numberOfViolationsExpected === 0) {
            $this->assertNoViolation();
        } else {
            $returnedViolations = $this->context->getViolations();
            $this->assertEquals($numberOfViolationsExpected, $returnedViolations->count());
            $count = 0;
            foreach ($violations as $violation) {
                $this->assertEquals($constraint->$violation, $returnedViolations->get($count)->getMessageTemplate());
                $count++;
            }
        }
    }

    public function boxDataProvider(): array
    {
        return [
            [true, null, null, 0, []],
            [false, null, null, 1, ['isNotOpenMessage']],
            [true, 'yesterday', null, 0, []],
            [true, 'tomorrow', null, 1, ['hasNotStartedMessage']],
            [true, null, 'tomorrow', 0, []],
            [true, null, 'yesterday', 1, ['hasEndedMessage']],
            [false, 'tomorrow', null, 2, ['isNotOpenMessage', 'hasNotStartedMessage']],
            [false, null, 'yesterday', 2, ['isNotOpenMessage', 'hasEndedMessage']],
        ];
    }

    protected function createValidator(): SuggestionBoxValidator
    {
        return new SuggestionBoxValidator();
    }
}
