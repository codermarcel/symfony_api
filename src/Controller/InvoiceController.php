<?php
namespace App\Controller;

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Repository\InvoiceRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/new', methods: ["GET", "POST"])]
    public function create(ManagerRegistry $mr): Response
    {
        $item1 = (new InvoiceItem())
            ->setName("Pen")
            ->setPriceCents(100)
            ->setQuantity(3);

        $item2 = (new InvoiceItem())
            ->setName("Paper")
            ->setPriceCents(20)
            ->setQuantity(1);

        $invoice = (new Invoice())
            ->setStatus(\InvoiceStatus::Created)
            ->addInvoiceItem($item1)
            ->addInvoiceItem($item2);

        $em = $mr->getManager();
        $em->persist($item1);
        $em->persist($item2);
        $em->persist($invoice);
        $em->flush();

        //prevent circular reference when serializing the object to json
        $invoice->clearInvoiceItems();

        return $this->json(["invoice" => $invoice]);
    }
}