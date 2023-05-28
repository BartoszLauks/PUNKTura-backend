<?php

namespace App\Entity;

use App\Repository\CfpEventsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CfpEventsRepository::class)]
class CfpEvents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fullname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $handle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $year = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $begindate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $finishdate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $submitdate = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notifydate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $weblink = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $info = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $UpdatedAt = null;

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

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(?string $fullname): self
    {
        $this->fullname = $fullname;

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

    public function getBegindate(): ?string
    {
        return $this->begindate;
    }

    public function setBegindate(?string $begindate): self
    {
        $this->begindate = $begindate;

        return $this;
    }

    public function getFinishdate(): ?string
    {
        return $this->finishdate;
    }

    public function setFinishdate(?string $finishdate): self
    {
        $this->finishdate = $finishdate;

        return $this;
    }

    public function getSubmitdate(): ?string
    {
        return $this->submitdate;
    }

    public function setSubmitdate(?string $submitdate): self
    {
        $this->submitdate = $submitdate;

        return $this;
    }

    public function getNotifydate(): ?string
    {
        return $this->notifydate;
    }

    public function setNotifydate(?string $notifydate): self
    {
        $this->notifydate = $notifydate;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->UpdatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $UpdatedAt): self
    {
        $this->UpdatedAt = $UpdatedAt;

        return $this;
    }
}
