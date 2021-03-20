<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\SuggestionBoxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hashids\Hashids;
use Symfony\Bridge\Doctrine\IdGenerator\UlidGenerator;
use Symfony\Component\Uid\Ulid;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=SuggestionBoxRepository::class)
 * @ORM\HasLifecycleCallbacks()
 * @ApiResource(
 *     collectionOperations={
 *         "get",
 *         "post"
 *     },
 *     itemOperations={
 *         "get",
 *         "patch",
 *         "delete"
 *     }
 * )
 */
class Box
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
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    private string $name;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     */
    private string $hash;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @ApiProperty(iri="http://schema.org/startTime")
     * @Assert\LessThan(propertyPath="endDatetime")
     */
    private ?\DateTimeInterface $startDatetime;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @ApiProperty(iri="http://schema.org/endTime")
     * @Assert\GreaterThan(propertyPath="startDatetime")
     */
    private ?\DateTimeInterface $endDatetime;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $isOpen = false;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private bool $isPublic = false;

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

    /**
     * @ORM\OneToMany(targetEntity=SuggestionType::class, mappedBy="box", orphanRemoval=true)
     * @ApiProperty(readable=false)
     * @ApiSubresource(maxDepth=1)`
     */
    private Collection $suggestionTypes;

    /**
     * @ORM\OneToMany(targetEntity=Suggestion::class, mappedBy="box", orphanRemoval=true)
     * @ApiProperty(readable=false)
     * @ApiSubresource(maxDepth=1)
     */
    private Collection $suggestions;

    public function __construct()
    {
        $this->suggestionTypes = new ArrayCollection();
        $this->suggestions = new ArrayCollection();
    }

    public function getId(): ?Ulid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getStartDatetime(): ?\DateTimeInterface
    {
        return $this->startDatetime;
    }

    public function setStartDatetime(?\DateTimeInterface $startDatetime): Box
    {
        $this->startDatetime = $startDatetime;
        return $this;
    }

    public function getEndDatetime(): ?\DateTimeInterface
    {
        return $this->endDatetime;
    }

    public function setEndDatetime(?\DateTimeInterface $endDatetime): Box
    {
        $this->endDatetime = $endDatetime;
        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setIsOpen(bool $isOpen): self
    {
        $this->isOpen = $isOpen;

        return $this;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;
        return $this;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
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

    /**
     * @return Collection|SuggestionType[]
     */
    public function getSuggestionTypes(): Collection
    {
        return $this->suggestionTypes;
    }

    /**
     * @return Collection|Suggestion[]
     */
    public function getSuggestions(): Collection
    {
        return $this->suggestions;
    }

    /**
     * @ORM\PrePersist
     */
    public function setHashId()
    {
        $hash = new Hashids();
        $this->hash = $hash->encode(time());
    }
}
