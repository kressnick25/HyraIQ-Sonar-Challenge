<?php

declare(strict_types=1);

namespace App\Payslip;

use App\Util\CurrencyFormatter;
use App\Util\PercentageFormatter;

final class SuperannuationSection implements SectionInterface
{
    /** @var SuperannuationItem[] */
    private $items = [];

    /** @var float */
    private $grossPay;

    public function __construct(float $grossPay)
    {
        $this->grossPay = $grossPay;
    }

    public function getTitle(): string
    {
        return 'SUPERANNUATION';
    }

    public function getHeadings(): array
    {
        return [
            'Fund',
            'Percentage',
            'Total',
        ];
    }

    public function addItem(SuperannuationItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function getRows(): array
    {
        return \array_map(function (SuperannuationItem $item): array {
            return [
                \ucfirst($item->getFund()),
                PercentageFormatter::format($item->getPercentage()),
                CurrencyFormatter::format($this->grossPay * $item->getPercentage()),
            ];
        }, $this->items);
    }

    public function getTotal(): float
    {
        /** @var float $total */
        $total = \array_reduce(
            $this->items,
            function (float $carry, SuperannuationItem $item): float {
                return $carry + $item->getPercentage() * $this->grossPay;
            },
            0
        );

        return $total;
    }
}
