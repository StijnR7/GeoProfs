<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestRepository::class)]
#[ORM\Table(name: "test")]
class Test
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $tekst = null;

  
    public function getTekst(): ?string
    {
        return $this->tekst;
    }

    public function setTekst(string $tekst): self
    {
        $this->tekst = $tekst;
        return $this;
    }
}
