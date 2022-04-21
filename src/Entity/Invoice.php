<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use InvoiceStatus;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
#[ORM\Table("invoices")]
class Invoice
{
    public function __construct()
    {
        $this->uuid = (Uuid::uuid4())->toString();
        $this->invoice_items = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->updated_at = new \DateTimeImmutable();
        $this->amount_cents = 0;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $updated_at;

    #[ORM\Column(type: 'integer')]
    private int $amount_cents;

    #[ORM\Column(type: 'string')]
    private string $uuid;


    private InvoiceStatus $status;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceItem::class)]
    private Collection $invoice_items;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getInvoiceItems(): Collection
    {
        return $this->invoice_items;
    }

    public function addInvoiceItem(InvoiceItem $item): self
    {
        $this->invoice_items->add($item);
        $item->setInvoice($this);
        $this->amount_cents += $item->getPriceCents() * $item->getQuantity();

        return $this;
    }

    /**
     * @return InvoiceStatus
     */
    public function getStatus(): InvoiceStatus
    {
        return $this->status;
    }

    /**
     * @param InvoiceStatus $status
     * @return Invoice
     */
    public function setStatus(InvoiceStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Clear the invoice in the invoice items to prevent A circular reference when serializing to JSON
     * @return $this
     */
    public function clearInvoiceItems(): self
    {
        foreach ($this->invoice_items as $item) {
            /** @var InvoiceItem */
            $item->clearInvoice();
        }
        return $this;
    }
}
