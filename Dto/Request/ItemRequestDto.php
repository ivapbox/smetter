<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;

class ItemRequestDto
{
    /**
     * @Assert\NotBlank(message="Не указан id товара")
     */
    private int $productId;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive(message="Цена должна быть больше 0")
     */
    private int $price;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive(message="Количество должно быть больше 0")
     */
    private int $quantity;

    public function __construct(
        int $productId,
        int $price,
        int $quantity
    ) {
        $this->productId = $productId;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}