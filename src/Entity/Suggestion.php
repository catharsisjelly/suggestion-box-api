<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SuggestionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Uid\Ulid;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;

/**
 * @ORM\Entity(repositoryClass=SuggestionRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post" = { "security_post_denormalize" = "is_granted('SUGGESTION_CREATE', object)", "security_message"="Suggestion Box is not be open" }
 *     },
 *     itemOperations={
 *         "get",
 *         "patch" = { "security" = "is_granted('SUGGESTION_UPDATE', object)", "security_message"="Suggestion Box is not be open" },
 *         "delete"
 *     }
 * )
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
     */
    private string $value;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     * @ApiProperty(writable=false)
     */
    private \DateTimeInterface $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     * @ApiProperty(writable=false)
     */
    private \DateTimeInterface $updated;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
