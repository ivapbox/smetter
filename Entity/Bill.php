<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * В коде смысл Entity немного нарушен, они выглядят скорей как Dto, но допустим мы работаем без ORM
 */
class Bill
{
    private int $id;

    private int $sum;

    /** @var Item[]  */
    private array $items = [];

    private int $type;

    private bool $isPaid;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSum(): int
    {
        return $this->sum;
    }

    public function setSum(int $sum): self
    {
        $this->sum = $sum;

        return $this;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     */
    public function setItems(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isPaid(): bool
    {
        return $this->isPaid;
        // закомментировал, т.к. здесь не должно быть подобных проверок с подключением сторонних микросервисов, тем более без обертки в try-catch
//        if ($this->type === BillType::PARTNER) {
//            return $this->isPaid;
//        }
//        if ($this->type === BillType::OTHER) {
//            return $this->billMicroserviceClient->isPaid($this->getId());
//        }
    }
}