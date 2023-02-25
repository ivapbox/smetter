<?php

namespace App\Controller;

use App\Factory\BillFactory;
use App\Repository\BillRepository;
use App\Service\BillGenerator;
use App\Service\BillMicroserviceClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use const App\Entity\BILL_TYPE_OTHER;
use const App\Entity\BILL_TYPE_Partner;

class BillController
{
    protected $bill_factory;
    protected $bill_repository;

    public function __construct(BillFactory $bill_factory, BillRepository $bill_repository)
    {
        $this->bill_factory = $bill_factory;
        $this->bill_repository = $bill_repository;
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $billData = json_decode($request->getContent(), true);
        $billId = $this->bill_factory->generateBillId();
        $bill = $this->bill_factory->createBill($billData, $billId);
        if ($bill->billType === BILL_TYPE_Partner) {
            $this->bill_repository->save($bill);
            return new RedirectResponse($bill->getPayUrl());
        }
        if ($bill->billType === BILL_TYPE_OTHER) {
            $bill->setBillGenerator(new BillGenerator());
            $this->bill_repository->save($bill);
            return new RedirectResponse($bill->getBillUrl());
        }
    }

    /**
     * @Route("/finish/{billId}", methods={"GET"})
     */
    public function finish($billId)
    {
        $bill = $this->bill_repository->get($billId);
        if ($bill->billType == BILL_TYPE_OTHER) {
            $bill->setBillClient(new BillMicroserviceClient());
        }
        if ($bill->isPaid()) {
            return new Response("Thank you");
        } else {
            return new Response("You haven't paid bill yet");
        }
    }

    /**
     * @Route("/last", methods={"GET"})
     */
    public function last(Request $request)
    {
        $limit = $request->get("limit");
        $bills = $this->bill_repository->last($limit);
        return new JsonResponse($bills);
    }
}