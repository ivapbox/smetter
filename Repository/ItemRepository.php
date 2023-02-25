<?php

namespace App\Repository;

use App\Entity\Item;

class ItemRepository implements ItemRepositoryInterface
{
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return Item[]
     */
    public function getBillItems(int $billId): array
    {
        $sql = "SELECT * FROM item WHERE bill_id={$billId}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        return array_map(static function (array $item) {
            return new Item(
                $item['bill_id'],
                $item['product_id'],
                $item['price'],
                $item['quantity'],
            );
        }, $data);
    }
}