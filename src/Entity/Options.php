<?php

namespace App\Entity;

use App\Repository\OptionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionsRepository::class)]
class Options
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $option_name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $option_value = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptionName(): ?string
    {
        return $this->option_name;
    }

    public function setOptionName(string $option_name): static
    {
        $this->option_name = $option_name;

        return $this;
    }

    public function getOptionValue(): ?string
    {
        return $this->option_value;
    }

    public function setOptionValue(?string $option_value): static
    {
        $this->option_value = $option_value;

        return $this;
    }
}
