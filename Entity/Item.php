<?php

namespace App\Entity;

class Item
{
    protected $id;
    protected $billId;
    protected $productId;
    protected $price;
    protected $quantity;

    public function __construct($billId, $productId, $price, $quantity)
    {
        $this->billId = $billId;
        $this->productId = $productId;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getBillId()
    {
        return $this->billId;
    }

    public function setBillId(int $billId)
    {
        $this->billId = $billId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice(int $price)
    {
        $this->price = $price;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;
    }
}