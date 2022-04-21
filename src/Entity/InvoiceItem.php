<?php

namespace App\Entity;

use App\Repository\InvoiceItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceItemRepository::class)]
class InvoiceItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    /**
     * The price per one unit
     * @var int
     */
    #[ORM\Column(type: 'integer')]
    private int $price_cents;

    #[ORM\Column(type: 'integer')]
    private int $quantity;

    #[ORM\ManyToOne(inversedBy: 'invoice_items')]
    private ?Invoice $invoice;

    public function getId(): ?int
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

    public function getPriceCents(): ?int
    {
        return $this->price_cents;
    }

    public function setPriceCents(int $price_cents): self
    {
        $this->price_cents = $price_cents;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(Invoice $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function clearInvoice(): self
    {
        $this->invoice = null;
        return $this;
    }
}
