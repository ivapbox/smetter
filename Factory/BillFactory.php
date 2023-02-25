<?php

namespace App\Factory;

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

    public function generateBillId()
    {
        $sql = "SELECT id FROM bills ORDER BY createdAt DESC LIMIT 1";
        $result = $this->pdo->query($sql)->fetch();
        return (new \DateTime())->format("Y-m") . "-" . $result['id'] + 1;
    }

    public function createBill($data, $id)
    {
        $bill = new Bill($id);
        foreach ($data as $key => $value) {
            if ($key == 'items') {
                foreach ($value as $itemValue) {
                    $bill->items[] = new Item($id, $itemValue['productId'],
                        $itemValue['price'], $itemValue['quantity']);
                }
                continue;
            }
            $bill->{$key} = $value;
        }
        return $bill;
    }
}