<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\Bill;

class BillRepository
{
    protected $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Bill $bill)
    {
        $sql = "INSERT INTO bills (id, sum, bill_type) VALUES ({$bill->id}, {$bill->sum}, {$bill->billType})";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        foreach ($bill->items as $item) {
            $sql = "INSERT
INTO bill_positions (bill_id,product_id,price,quantity)
VALUES ({$bill->id},{$item->getProductId()},{$item->getPrice()},
{$item->getQuantity()})";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
        }
    }

    public function get($billId)
    {
        $sql = "SELECT * FROM bills WHERE id={$billId} LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $data = $stmt->fetch();
        $bill = new Bill($data['id']);
        $bill->billType = $data['bill_type'];
        $bill->isPaid = $data['is_paid'];
        $bill->sum = $data['sum'];
        $bill->items = $this->getBillItems($data['id']);
        return $bill;
    }

    public function last($limit = 10)
    {
        $sql = "SELECT * FROM bills ORDER BY createdAt DESC LIMIT {$limit}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $bills = [];
        foreach ($data as $item) {
            $bill = new Bill($item['id']);
            $bill->billType = $item['bill_type'];
            $bill->isPaid = $item['is_paid'];
            $bill->sum = $item['sum'];
            $bill->items = $this->getBillItems($item['id']);
            $bills[] = $bill;
        }
        return $bills;
    }

    public function getBillItems($billId)
    {
        $sql = "SELECT * FROM bill_positions WHERE bill_id={$billId}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $items = [];
        foreach ($data as $item) {
            $items[] = new Item($item['bill_id'], $item['product_id'], $item['price'], $item['quantity']);
        }
        return $items;
    }
}