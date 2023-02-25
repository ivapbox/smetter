<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * В коде смысл Entity немного нарушен, они выглядят скорей как Dto, но допустим мы работаем без ORM
 */
class Item
{
    private int $id;
    private int $billId;
    private int $productId;
    private int $price;
    private int $quantity;

    public function __construct(int $billId, int $productId, int $price, int $quantity)
    {
        $this->billId = $billId;
        $this->productId = $productId;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getBillId(): int
    {
        return $this->billId;
    }

    public function setBillId(int $billId)
    {
        $this->billId = $billId;

        return $this;
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

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}