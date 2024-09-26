<?php

namespace App\Entity;

use App\Repository\FavoriteHeroesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteHeroesRepository::class)]
class FavoriteHeroes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $favorite_id = null;

    // ManyToOne relationship with the User entity
    #[ORM\ManyToOne(targetEntity: Favorite::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Favorite $favorite = null;

    #[ORM\Column]
    private ?int $hero_id = null;

    #[ORM\Column(type: 'datetime_immutable', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFavoriteId(): ?int
    {
        return $this->favorite_id;
    }

    public function setFavoriteId(int $favorite_id): static
    {
        $this->favorite_id = $favorite_id;

        return $this;
    }

    public function getFavorite(): ?Favorite
    {
        return $this->favorite;
    }

    public function setFavorite(?Favorite $favorite): self
    {
        $this->favorite = $favorite;

        return $this;
    }

    public function getHeroId(): ?int
    {
        return $this->hero_id;
    }

    public function setHeroId(int $hero_id): static
    {
        $this->hero_id = $hero_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
