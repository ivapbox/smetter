<?php

namespace App\Resolver;

use App\Dto\Request\BillRequestDto;
use App\Dto\Request\ItemRequestDto;
use App\Type\BillType;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;

/**
 * В идеале можно использовать более продвинутый способ:
 *      сделать нормальный deserializer с проверкой Assert'ов, тогда Assert\Valid сработают и получится красивая DTOшка
 * Но я оставлю более упрощенный вариант, все-таки ревью а не полное переписывание кода :)
 * @see App\Dto\Request\BillRequestDto
 */
class BillCreateArgumentResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return BillRequestDto::class === $argument->getType();
    }

    /**
     * @return iterable<BillRequestDto>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $billData = json_decode($request->getContent(), true);

        $itemDtos = array_map(static function (array $itemRequest) {
            return new ItemRequestDto(
                $itemRequest['productId'] ?? 0,
                $itemRequest['price'] ?? 0,
                $itemRequest['quantity'] ?? 0,
            );
        }, $billData['items']);

        yield new BillRequestDto($itemDtos, $itemRequest['type'] ?? BillType::OTHER);
    }
}