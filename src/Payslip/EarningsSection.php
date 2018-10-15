<?php

declare(strict_types=1);

namespace App\Payslip;

use App\Util\CurrencyFormatter;

final class EarningsSection implements SectionInterface
{
    /** @var EarningsItem[] */
    private $items = [];

    public function getTitle(): string
    {
        return 'SALARY & WAGES';
    }

    public function getHeadings(): array
    {
        return [
            'Shift Type',
            'Hours Worked',
            'Rate',
            'Total',
        ];
    }

    public function addItem(EarningsItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function getRows(): array
    {
        return \array_map(function (EarningsItem $item): array {
            return [
                \ucfirst($item->getShiftType()),
                $item->getHours(),
                CurrencyFormatter::format($item->getRate()),
                CurrencyFormatter::format($item->getTotal()),
            ];
        }, $this->items);
    }

    public function getTotal(): float
    {
        /** @var float $total */
        $total = \array_reduce(
            $this->items,
            function (float $carry, EarningsItem $item): float {
                return $carry + $item->getTotal();
            },
            0
        );

        return $total;
    }
}
