<?php

declare(strict_types=1);

namespace App\Type;

class BillType
{
    public const PARTNER = 1;
    public const OTHER = 2;

    public const ALLOWED_TYPES = [
        self::PARTNER,
        self::OTHER
    ];
}