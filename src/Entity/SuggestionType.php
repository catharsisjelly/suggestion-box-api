<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\SuggestionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Ulid;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SuggestionTypeRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "get",
 *         "post"
 *     },
 *     itemOperations={
 *         "get",
 *         "patch",
 *         "delete"
 *     }
 * )
 */
class SuggestionType
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="ulid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UlidGenerator::class)
     * @ApiProperty(iri="https://schema.org/identifier", identifier=true, writable=false)
     */
    private Ulid $id;

    /**
     * @ORM\ManyToOne(targetEntity=Box::class, inversedBy="suggestionTypes")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=false)
     */
    private Box $box;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @ApiProperty(iri="https://schema.org/name", required=true)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private string $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @ApiProperty(iri="https://schema.org/description")
     */
    private ?string $description;

    /**
     * @ORM\OneToMany(targetEntity=Suggestion::class, mappedBy="suggestionType")
     * @ApiSubresource(maxDepth=1)
     */
    private Collection $suggestions;

    public function __construct()
    {
        $this->suggestions = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): SuggestionType
    {
        $this->description = $description;
        return $this;
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

    /**
     * @return Collection|Suggestion[]
     */
    public function getSuggestions(): Collection
    {
        return $this->suggestions;
    }
}
