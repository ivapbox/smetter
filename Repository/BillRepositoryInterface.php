<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Bill;
use App\Entity\Item;

interface BillRepositoryInterface
{
    public function save(Bill $bill): void;

    public function get(int $billId): ?Bill;

    /**
     * @return Bill[]
     */
    public function last(int $limit = 10): array;

    /**
     * @return Item[]
     */
    public function getBillItems($billId): array;
}