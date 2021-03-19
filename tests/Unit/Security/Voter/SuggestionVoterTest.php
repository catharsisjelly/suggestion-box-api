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
        $box->setStartDatetime(null);
        $box->setEndDatetime(null);
        $suggestion = new Suggestion();
        $suggestion->setBox($box);

        return [
            [$box, [], VoterInterface::ACCESS_ABSTAIN],
            [$suggestion, [], VoterInterface::ACCESS_ABSTAIN],
            [$suggestion, [SuggestionVoter::SUGGESTION_POST], VoterInterface::ACCESS_GRANTED],
            [$suggestion, [SuggestionVoter::SUGGESTION_UPDATE], VoterInterface::ACCESS_GRANTED]
        ];
    }

    /**
     * @dataProvider voteOnAttributeProvider
     * @param object $subject
     * @param array $attributes
     * @param int $expected
     */
    public function test_voteOnAttribute(object $subject, array $attributes, int $expected)
    {
        $mockToken = m::mock(TokenInterface::class);
        $voter = new SuggestionVoter();
        $actual = $voter->vote($mockToken, $subject, $attributes);
        $this->assertEquals($actual, $expected);
    }

    /**
     * @return array[]
     */
    public function voteOnAttributeProvider(): array
    {
        return [
            [
                $this->createSuggestion(),
                [SuggestionVoter::SUGGESTION_POST, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_GRANTED
            ], // No Date time set for either
            [
                $this->createSuggestion(null, new \DateTimeImmutable('tomorrow')),
                [SuggestionVoter::SUGGESTION_POST, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_GRANTED
            ], // This box shuts tomorrow, so we can add suggestions
            [
                $this->createSuggestion(null, new \DateTimeImmutable('yesterday')),
                [SuggestionVoter::SUGGESTION_POST, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_DENIED
            ], // This box shut yesterday, so we cannot add suggestions
            [
                $this->createSuggestion(new \DateTimeImmutable('yesterday'), null),
                [SuggestionVoter::SUGGESTION_POST, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_GRANTED
            ], // This box opened yesterday, so we can add suggestions
            [
                $this->createSuggestion(new \DateTimeImmutable('tomorrow'), null),
                [SuggestionVoter::SUGGESTION_POST, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_DENIED
            ], // This box opens tomorrow, so we cannot add suggestions
            [
                $this->createSuggestion(
                    new \DateTimeImmutable('-10 seconds'),
                    new \DateTimeImmutable('-1 second')
                ),
                [SuggestionVoter::SUGGESTION_POST, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_DENIED
            ], // This box opened 10 seconds ago and shut 1 second ago
            [
                $this->createSuggestion(
                    new \DateTimeImmutable('-1 seconds'),
                    new \DateTimeImmutable('+1 second')
                ),
                [SuggestionVoter::SUGGESTION_POST, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_GRANTED
            ], // This box opened 1 second ago and is still open
            [
                $this->createSuggestion(
                    new \DateTimeImmutable('+10 seconds'),
                    new \DateTimeImmutable('+10 minutes')
                ),
                [SuggestionVoter::SUGGESTION_POST, SuggestionVoter::SUGGESTION_UPDATE],
                VoterInterface::ACCESS_DENIED
            ], // This box opens in 10 seconds ago and closes in 10 minutes
        ];
    }

    /**
     * @param \DateTimeInterface|null $startDatetime
     * @param \DateTimeInterface|null $endDatetime
     * @return Suggestion
     */
    private function createSuggestion(
        ?\DateTimeInterface $startDatetime = null,
        ?\DateTimeInterface $endDatetime = null
    ): Suggestion {
        $box = new Box();
        $box->setStartDatetime($startDatetime);
        $box->setEndDatetime($endDatetime);
        $suggestion = new Suggestion();
        $suggestion->setBox($box);

        return $suggestion;
    }
}
