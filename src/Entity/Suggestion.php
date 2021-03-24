<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\SuggestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\Ulid;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SuggestionRepository::class)
 * @see config/api_platform/resources.yaml
 * @ApiFilter(OrderFilter::class, properties={"created"})
 * @ApiFilter(BooleanFilter::class, properties={"discarded"})
 * @ApiFilter(SearchFilter::class, properties={"box.id": "exact", "suggestionType.id": "exact"})
 */
class Suggestion
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     * @ApiProperty(iri="http://schema.org/identity", identifier=true, writable=false)
     */
    private Ulid $id;

    /**
     * @ORM\ManyToOne(targetEntity=Box::class, inversedBy="suggestions")
     * @ORM\JoinColumn(nullable=false)
     */
    private Box $box;

    /**
     * @ORM\ManyToOne(targetEntity=SuggestionType::class, inversedBy="suggestions")
     * @ORM\JoinColumn(nullable=false)
     */
    private SuggestionType $suggestionType;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private string $value;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $discarded = false;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @ApiProperty(writable=false)
     */
    private \DateTimeInterface $created;

    public function __construct()
    {
        $this->id = new Ulid();
        $this->created = new \DateTimeImmutable();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getBox(): ?Box
    {
        return $this->box;
    }

    public function setBox(Box $box): self
    {
        $this->box = $box;

        return $this;
    }

    public function getSuggestionType(): ?SuggestionType
    {
        return $this->suggestionType;
    }

    public function setSuggestionType(SuggestionType $suggestionType): self
    {
        $this->suggestionType = $suggestionType;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function isDiscarded(): bool
    {
        return $this->discarded;
    }

    public function setDiscarded(bool $discarded): self
    {
        $this->discarded = $discarded;
        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }
}
