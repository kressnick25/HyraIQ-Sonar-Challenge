<?php

namespace App\Payslip;

final class Payslip
{
    /** @var SectionInterface[] */
    private $sections = [];

    public function addSection(SectionInterface $section): self
    {
        $this->sections[] = $section;

        return $this;
    }

    /**
     * @return SectionInterface[]
     */
    public function getSections(): array
    {
        return $this->sections;
    }
}
