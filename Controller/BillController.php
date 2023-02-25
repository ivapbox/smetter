<?php

namespace App\Controller;

use App\Dto\Request\BillRequestDto;
use App\Exception\BillNotPaidException;
use App\Exception\BillUndefiledTypeException;
use App\Service\BillGenerator;
use App\Service\BillMicroserviceClient;
use App\Service\BillService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class BillController
{
    /**
     * Полагаю - тело запроса выглядит так:
     *
     * {
     *       "type": 1,
     *       "items": [
     *           {
     *               "productId": 1,
     *               "price": 100,
     *               "quantity": 1
     *           },
     *           {
     *               "productId": 2,
     *               "price": 200,
     *               "quantity": 2
     *           },
     *           {
     *               "productId": 3,
     *               "price": 300,
     *               "quantity": 3
     *           }
     *       ]
     * }
     *
     * @Route("/create", methods={"POST"})
     */
    public function create(BillRequestDto $request, BillService $billService)
    {
        try {
            $bill = $billService->create($request);
            return new RedirectResponse($billService->getBillUrl($bill));
        } catch (BillUndefiledTypeException) {
            return new JsonResponse([
                "status" => "error",
                "message" => "Bad Request"
            ]);
        } catch (Throwable $exception) {
            return new JsonResponse([
                "status" => "error",
                "message" => "Что-то пошло не так"
            ]);
        }
    }

    /**
     * @Route("/finish/{billId}", methods={"POST"})
     */
    public function finish(int $billId, BillService $billService)
    {
        try {
            $billService->finish($billId);
            return new JsonResponse([
                "status" => "success",
            ]);
        } catch (BillNotPaidException $exception) {
            return new JsonResponse([
                "status" => "error",
                "message" => "You haven't paid bill yet"
            ]);
        } catch (Throwable $exception) {
            return new JsonResponse([
                "status" => "error",
                "message" => "Что-то пошло не так"
            ]);
        }
    }

    /**
     * @Route("/last", methods={"GET"})
     */
    public function last(Request $request, BillService $billService)
    {
        $limit = $request->get("limit");
        return new JsonResponse($billService->getLast($limit));
    }
}