<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Suggestion;
use Doctrine\ORM\EntityManagerInterface;

class SuggestionDataPersister implements ContextAwareDataPersisterInterface
{
    private EntityManagerInterface $entityManager;
    private ContextAwareDataPersisterInterface $decorated;

    /**
     * @param ContextAwareDataPersisterInterface $decorated
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ContextAwareDataPersisterInterface $decorated, EntityManagerInterface $entityManager)
    {
        $this->decorated = $decorated;
        $this->entityManager = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        return $this->decorated->supports($data, $context);
    }

    /**
     * @param Suggestion $data
     * @param array $context
     * @return object|void
     */
    public function persist($data, array $context = [])
    {
        if ($data instanceof Suggestion && ($context['item_operation_name'] ?? null) === 'patch') {
            $originalData = $this->entityManager->getUnitOfWork()->getOriginalEntityData($data);
            // if we are revert any change that is not the discarded property
            $keysToReset = ['value', 'suggestionType', 'box'];
            foreach ($keysToReset as $key) {
                if (isset($originalData[$key])) {
                    call_user_func([$data, 'set' . $key], $originalData[$key]);
                }
            }
        }

        return $this->decorated->persist($data, $context);
    }

    public function remove($data, array $context = [])
    {
        return $this->decorated->persist($data, $context);
    }
}
