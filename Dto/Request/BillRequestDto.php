<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\Validator\Constraints as Assert;
use App\Type\BillType;

class BillRequestDto
{
    /**
     * @var ItemRequestDto[]
     *
     * @Assert\Valid
     */
    private array $items;

    /**
     * @Assert\Choice(choices=BillType::ALLOWED_TYPES)
     */
    private int $type;

    /**
     * @param ItemRequestDto[] $items
     */
    public function __construct(array $items, int $type)
    {
        $this->items = $items;
        $this->type = $type;
    }

    /**
     * @return ItemRequestDto[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getType(): int
    {
        return $this->type;
    }
}