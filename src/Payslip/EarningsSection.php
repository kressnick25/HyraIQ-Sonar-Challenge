<?php

namespace App\Payslip;

use App\Core\CurrencyFormatter;

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
        return \array_reduce(
            $this->items,
            function (int $carry, EarningsItem $item): int {
                return $carry + $item->getTotal();
            },
            0
        );
    }
}
