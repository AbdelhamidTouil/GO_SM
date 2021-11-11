<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StockRepository::class)
 */
class Stock
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Produit::class, inversedBy="stocks")
     */
    private $produits;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $quantite;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $prix_unitaire;

    /**
     * @ORM\Column(type="date")
     */
    private $date_stockage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduits(): ?produit
    {
        return $this->produits;
    }

    public function setProduits(?produit $produits): self
    {
        $this->produits = $produits;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire(string $prix_unitaire): self
    {
        $this->prix_unitaire = $prix_unitaire;

        return $this;
    }

    public function getDateStockage(): ?\DateTimeInterface
    {
        return $this->date_stockage;
    }

    public function setDateStockage(\DateTimeInterface $date_stockage): self
    {
        $this->date_stockage = $date_stockage;

        return $this;
    }
}
