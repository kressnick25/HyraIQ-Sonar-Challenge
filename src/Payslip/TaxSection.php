<?php

declare(strict_types=1);

namespace App\Payslip;

use App\Util\CurrencyFormatter;
use App\Util\PercentageFormatter;

final class TaxSection implements SectionInterface
{
    /** @var float */
    private $grossPay;

    /** @var TaxItem[] */
    private $items = [];

    public function __construct(float $grossPay)
    {
        $this->grossPay = $grossPay;
    }

    public function getTitle(): string
    {
        return 'TAX';
    }

    public function getHeadings(): array
    {
        return [
            'Tax Type',
            'Rate',
            'Total',
        ];
    }

    public function addItem(TaxItem $item): self
    {
        $this->items[] = $item;

        return $this;
    }

    public function getRows(): array
    {
        return \array_map(
            function (TaxItem $item): array {
                return [
                    \ucfirst($item->getTaxType()),
                    PercentageFormatter::format($item->getRate()),
                    CurrencyFormatter::format($this->grossPay * $item->getRate()),
                ];
            },
            $this->items
        );
    }

    public function getTotal(): float
    {
        /** @var float $total */
        $total = \array_reduce(
            $this->items,
            function (float $carry, TaxItem $item): float {
                return $carry + $item->getRate() * $this->grossPay;
            },
            0
        );

        return $total;
    }
}
