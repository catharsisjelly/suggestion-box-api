<?php

namespace App\Tests\Unit\Security\Voter;

use App\Entity\Box;
use App\Entity\Suggestion;
use App\Security\Voter\SuggestionVoter;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class SuggestionVoterTest extends TestCase
{
    /**
     * @dataProvider supportsProvider
     * @param object $subject
     * @param array $attributes
     * @param int $expected
     */
    public function test_supports(object $subject, array $attributes, int $expected)
    {
        $mockToken = m::mock(TokenInterface::class);
        $voter = new SuggestionVoter();
        $actual = $voter->vote($mockToken, $subject, $attributes);
        $this->assertEquals($actual, $expected);
    }

    public function supportsProvider(): array
    {
        $box = new Box();
        $box->setIsOpen(true);
        $box->setStartDatetime(null);
        $box->setEndDatetime(null);
        $suggestion = new Suggestion();
        $suggestion->setBox($box);

        return [
            [$box, [], VoterInterface::ACCESS_ABSTAIN],
            [$suggestion, [], VoterInterface::ACCESS_ABSTAIN],
            [$suggestion, [SuggestionVoter::SUGGESTION_CREATE], VoterInterface::ACCESS_GRANTED],
            [$suggestion, [SuggestionVoter::SUGGESTION_UPDATE], VoterInterface::ACCESS_GRANTED]
        ];
    }

    /**
     * @dataProvider voteOnAttributeIsOpenProvider
     * @param Suggestion $subject
     * @param array $attributes
     * @param int $expected
     */
    public function test_voteOnAttribute_box_is_open(Suggestion $subject, array $attributes, int $expected)
    {
        $mockToken = m::mock(TokenInterface::class);
        $voter = new SuggestionVoter();
        $actual = $voter->vote($mockToken, $subject, $attributes);
        $this->assertEquals($actual, $expected);
    }

    public function voteOnAttributeIsOpenProvider()
    {
        return [
            [
                $this->createSuggestion(false),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_DENIED
            ],
            [
                $this->createSuggestion(true),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_GRANTED
            ],
        ];
    }

    private function createSuggestion(bool $isOpen)
    {
        $box = new Box();
        $box->setIsOpen($isOpen);
        $box->setStartDatetime(null);
        $box->setEndDatetime(null);
        $suggestion = new Suggestion();
        $suggestion->setBox($box);

        return $suggestion;
    }

    /**
     * @dataProvider timeboxedSuggestionProvider
     * @param Suggestion $subject
     * @param array $attributes
     * @param int $expected
     */
    public function test_voteOnAttribute_start_end_date(Suggestion $subject, array $attributes, int $expected)
    {
        $mockToken = m::mock(TokenInterface::class);
        $voter = new SuggestionVoter();
        $actual = $voter->vote($mockToken, $subject, $attributes);
        $this->assertEquals($actual, $expected);
    }

    /**
     * @return array[]
     */
    public function timeboxedSuggestionProvider(): array
    {
        return [
            [
                $this->createTimeBoxedSuggestion(),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_GRANTED
            ], // No Date time set for either
            [
                $this->createTimeBoxedSuggestion(null, new \DateTimeImmutable('tomorrow')),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_GRANTED
            ], // This box shuts tomorrow, so we can add suggestions
            [
                $this->createTimeBoxedSuggestion(null, new \DateTimeImmutable('yesterday')),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_DENIED
            ], // This box shut yesterday, so we cannot add suggestions
            [
                $this->createTimeBoxedSuggestion(new \DateTimeImmutable('yesterday'), null),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_GRANTED
            ], // This box opened yesterday, so we can add suggestions
            [
                $this->createTimeBoxedSuggestion(new \DateTimeImmutable('tomorrow'), null),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_DENIED
            ], // This box opens tomorrow, so we cannot add suggestions
            [
                $this->createTimeBoxedSuggestion(
                    new \DateTimeImmutable('-10 seconds'),
                    new \DateTimeImmutable('-1 second')
                ),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_DENIED
            ], // This box opened 10 seconds ago and shut 1 second ago
            [
                $this->createTimeBoxedSuggestion(
                    new \DateTimeImmutable('-1 seconds'),
                    new \DateTimeImmutable('+1 second')
                ),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_GRANTED
            ], // This box opened 1 second ago and is still open
            [
                $this->createTimeBoxedSuggestion(
                    new \DateTimeImmutable('+10 seconds'),
                    new \DateTimeImmutable('+10 minutes')
                ),
                [SuggestionVoter::SUGGESTION_CREATE, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_DENIED
            ], // This box opens in 10 seconds ago and closes in 10 minutes
        ];
    }

    /**
     * @param \DateTimeInterface|null $startDatetime
     * @param \DateTimeInterface|null $endDatetime
     * @return Suggestion
     */
    private function createTimeBoxedSuggestion(
        ?\DateTimeInterface $startDatetime = null,
        ?\DateTimeInterface $endDatetime = null
    ): Suggestion {
        $box = new Box();
        $box->setIsOpen(true);
        $box->setStartDatetime($startDatetime);
        $box->setEndDatetime($endDatetime);
        $suggestion = new Suggestion();
        $suggestion->setBox($box);

        return $suggestion;
    }
}
