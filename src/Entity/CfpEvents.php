<?php

namespace App\Entity;

use App\Repository\CfpEventsRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CfpEventsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class CfpEvents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $UpdatedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $handle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $year = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $beginDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $finishDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $submitDate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notifyDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $weblink = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $cfpLink = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $beginDateFormat = null;

    #[ORM\Column( nullable: true)]
    private ?\DateTimeImmutable $submitDateFormat = null;

    #[ORM\Column( nullable: true)]
    private ?\DateTimeImmutable $finishDateFormat = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getHandle(): ?string
    {
        return $this->handle;
    }

    public function setHandle(?string $handle): self
    {
        $this->handle = $handle;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getBeginDate(): ?string
    {
        return $this->beginDate;
    }

    public function setBeginDate(?string $beginDate): self
    {
        $this->beginDate = $beginDate;

        return $this;
    }

    public function getFinishDate(): ?string
    {
        return $this->finishDate;
    }

    public function setFinishDate(?string $finishDate): self
    {
        $this->finishDate = $finishDate;

        return $this;
    }

    public function getSubmitDate(): ?string
    {
        return $this->submitDate;
    }

    public function setSubmitDate(?string $submitDate): self
    {
        $this->submitDate = $submitDate;

        return $this;
    }

    public function getNotifyDate(): ?string
    {
        return $this->notifyDate;
    }

    public function setNotifyDate(?string $notifyDate): self
    {
        $this->notifyDate = $notifyDate;

        return $this;
    }

    public function getWeblink(): ?string
    {
        return $this->weblink;
    }

    public function setWeblink(?string $weblink): self
    {
        $this->weblink = $weblink;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function getCfpLink(): ?string
    {
        return $this->cfpLink;
    }

    public function setCfpLink(?string $cfpLink): self
    {
        $this->cfpLink = $cfpLink;

        return $this;
    }

    public function getBeginDateFormat(): ?\DateTimeImmutable
    {
        return $this->beginDateFormat;
    }

    public function setBeginDateFormat(?\DateTimeImmutable $beginDateFormat): self
    {
        $this->beginDateFormat = $beginDateFormat;

        return $this;
    }

    public function getSubmitDateFormat(): ?DateTimeImmutable
    {
        return $this->submitDateFormat;
    }

    public function setSubmitDateFormat(?DateTimeImmutable $submitDateFormat): self
    {
        $this->submitDateFormat = $submitDateFormat;

        return $this;
    }

    public function getFinishDateFormat(): ?\DateTimeImmutable
    {
        return $this->finishDateFormat;
    }

    public function setFinishDateFormat(?\DateTimeImmutable $finishDateFormat): self
    {
        $this->finishDateFormat = $finishDateFormat;

        return $this;
    }

    #[ORM\PrePersist()]
    #[ORM\PreUpdate()]
    public function updatedTimestamp(): void
    {
        $this->setUpdatedAt(new \DateTimeImmutable('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable('now'));
        }
    }
}

