<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\Request\BillRequestDto;
use App\Entity\Bill;
use App\Exception\BillUndefiledTypeException;
use App\Factory\BillFactory;
use App\Repository\BillRepositoryInterface;
use App\Exception\BillNotPaidException;
use App\Type\BillType;
use App\Service\BillGenerator;

class BillService
{
    private BillRepositoryInterface $billRepository;
    private BillFactory $billFactory;
    private BillGenerator $billGenerator;

    public function __construct(
        BillRepositoryInterface $billRepository,
        BillFactory $billFactory,
        BillGenerator $billGenerator
    ) {
        $this->billRepository = $billRepository;
        $this->billFactory = $billFactory;
        $this->billGenerator = $billGenerator;
    }

    /**
     * @return Bill[]
     */
    public function getLast(int $limit): array
    {
        return $this->billRepository->last($limit);
    }

    public function create(BillRequestDto $billData): Bill
    {
        $bill = $this->billFactory->createBill($billData);
        $this->billRepository->save($bill);

        return $bill;
    }

    /**
     * @param int $billId
     * @return void
     * @throws BillNotPaidException
     */
    public function finish(int $billId): void
    {
        $bill = $this->billRepository->get($billId);
        if ($bill->getType() == BillType::OTHER) {
            // неясно для чего подключается клиент, закомментировал, но это явно некорректный код
//            $bill->setBillClient(new BillMicroserviceClient());
            // но если нужно поменять какое-то поле (к примеру is_paid) - то в конце не забываем добавить
            // $this->billRepository->save($bill);
        }
        if (!$bill->isPaid()) {
            throw new BillNotPaidException();
        }
    }

    /**
     * @throws BillUndefiledTypeException
     */
    public function getBillUrl(Bill $bill): string
    {
        switch ($bill->getType()) {
            case BillType::PARTNER:
                return "http://pay" . $bill->getId();
            case BillType::OTHER:
                return $this->billGenerator->generate($bill);
        }
        throw new BillUndefiledTypeException();
    }
}