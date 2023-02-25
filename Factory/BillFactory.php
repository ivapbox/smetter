<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\Request\BillRequestDto;
use App\Dto\Request\ItemRequestDto;
use App\Entity\Item;
use App\Entity\Bill;

class BillFactory
{
    /** @var \PDO */
    protected $pdo;

    /**
     * BillFactory constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Вообще при использовании ORM не нужны никакие генераторы, id сам сгенерится
     */
    private function generateBillId(): int
    {
        $sql = "SELECT id FROM bills ORDER BY created_at DESC LIMIT 1";
        $result = $this->pdo->query($sql)->fetch();
        return $result['id'] + 1;
    }

    public function createBill(BillRequestDto $data): Bill
    {
        $billId = $this->generateBillId();

        $requestItems = $data->getItems();

        $sum = 0;
        foreach ($requestItems as $item) {
            $sum += $item->getPrice();
        }

        return (new Bill($billId))
            ->setSum($sum)
            ->setItems(array_map(function (ItemRequestDto $itemRequestDto) use ($billId) {
                return new Item(
                    $billId,
                    $itemRequestDto->getProductId(),
                    $itemRequestDto->getPrice(),
                    $itemRequestDto->getQuantity(),
                );
            }, $requestItems))
            ->setType($data->getType());
    }
}