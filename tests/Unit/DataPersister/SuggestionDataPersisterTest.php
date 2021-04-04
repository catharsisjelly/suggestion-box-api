<?php

namespace App\Tests\Unit\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\DataPersister\SuggestionDataPersister;
use App\Entity\Box;
use App\Entity\Suggestion;
use App\Entity\SuggestionType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class SuggestionDataPersisterTest extends TestCase
{
    public function test_persist_with_context()
    {
        $mockContext = m::mock(ContextAwareDataPersisterInterface::class);
        $mockContext->shouldReceive('persist')
            ->once();

        $mockUnit = m::mock(UnitOfWork::class);
        $mockUnit->shouldReceive('getOriginalEntityData')
            ->once()
            ->andReturn([
                'box' => m::mock(Box::class),
                'suggestionType' => m::mock(SuggestionType::class),
                'value' => 'SomeValue'
            ]);

        $mockEntityManager = m::mock(EntityManagerInterface::class);
        $mockEntityManager->shouldReceive('getUnitOfWork')
            ->once()
            ->andReturn($mockUnit);

        $object = m::mock(Suggestion::class);
        $object->shouldReceive('setBox', 'setSuggestionType', 'setValue')
            ->once();

        $dataPersister = new SuggestionDataPersister($mockContext, $mockEntityManager);
        $return = $dataPersister->persist($object, ['item_operation_name' => 'patch']);
        $this->assertNull($return);
    }
}
