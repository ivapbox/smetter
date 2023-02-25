<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Item;

interface ItemRepositoryInterface
{
    /**
     * @return Item[]
     */
    public function getBillItems(int $billId): array;
}