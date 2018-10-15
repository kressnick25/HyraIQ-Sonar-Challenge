<?php

namespace App\Payslip;

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
                    $item->getTaxType(),
                    $item->getRate(),
                    $this->grossPay * $item->getRate(),
                ];
            },
            $this->items
        );
    }

    public function getTotal(): float
    {
        return \array_reduce(
            $this->items,
            function (int $carry, TaxItem $item): int {
                return $carry + $item->getRate() * $this->grossPay;
            },
            0
        );
    }
}
