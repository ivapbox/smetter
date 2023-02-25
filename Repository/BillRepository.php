<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Bill;

class BillRepository implements BillRepositoryInterface
{
    protected $pdo;

    private ItemRepositoryInterface $itemRepository;

    public function __construct(\PDO $pdo, ItemRepositoryInterface $itemRepository)
    {
        $this->pdo = $pdo;
        $this->itemRepository = $itemRepository;
    }

    public function save(Bill $bill): void
    {
        // здесь лучше обернуть в транзакцию
        // если через entityManager - try {$this->emMain->beginTransaction(); <логика>; ->commit();} catch () {->rollback()}
        try {
            $this->pdo->prepare('START TRANSACTION');
            $sql = "INSERT INTO bills (id, sum, bill_type) VALUES ({$bill->getId()}, {$bill->getSum()}, {$bill->getType()})";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            // лучше инсертить чанками, по нескольку штук (допустим по 100)
            // не нужно нагружать базу и инсертить каждый элемент отдельно
            foreach ($bill->getItems() as $item) {
                // ORM позволяет сразу пробиндить значения, защита от SQL-инъекций
                $sql = "INSERT INTO item (bill_id, product_id, price, quantity)
                            VALUES ({$bill->getId()},{$item->getProductId()},{$item->getPrice()}, {$item->getQuantity()})";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
            }
            $this->pdo->prepare('COMMIT;');

        } catch (\Throwable $exception) {
            $this->pdo->prepare('ROLLBACK;');
            throw $exception;
        }
    }

    public function get(int $billId): ?Bill
    {
        $sql = "SELECT * FROM bills WHERE id={$billId} LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $data = $stmt->fetch();
        return $this->denormalize($data);
    }

    /**
     * @return Bill[]
     */
    public function last(int $limit = 10): array
    {
        $sql = "SELECT * FROM bills ORDER BY createdAt DESC LIMIT {$limit}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        return array_map(static function (array $billArr) {
            return $this->denormalize($billArr);
        }, $data);
    }

    /**
     * @return Item[]
     */
    public function getBillItems($billId): array
    {
        $sql = "SELECT * FROM item WHERE bill_id={$billId}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $items = [];

        foreach ($data as $item) {
            $items[] = new Item($item['bill_id'], $item['product_id'], $item['price'], $item['quantity']);
        }

        return $items;
    }

    private function denormalize(array $billData): Bill
    {
        $items = $this->itemRepository->getBillItems($billData['id']);

        return (new Bill($billData['id']))
            ->setSum($billData['sum'])
            ->setType($billData['type'])
            ->setItems($items)
//            ->setIsPaid($billData['is_paid'])
        ;
    }
}