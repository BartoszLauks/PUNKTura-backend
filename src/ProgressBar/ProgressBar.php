<?php

declare(strict_types=1);

namespace App\ProgressBar;

use Symfony\Component\Console\Output\ConsoleSectionOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class ProgressBar
{
    private string $bar;

    private int $counter = 0;

    private int $total = 0;

    private int $oldPercentage = 0;

    private ConsoleSectionOutput $progressSection;

    public function __construct()
    {
        $this->bar = str_repeat('-', 50);
    }

    public function initialize(OutputInterface $output, int $total): void
    {
        $this->total = $total;
        $this->progressSection = $output->section();
    }

    public function advance(): void
    {
        $this->counter++;

        $percentage = (int) number_format(($this->counter / $this->total * 100));
        if ($percentage % 2 === 0 && $percentage !== $this->oldPercentage) {
            $pos = strpos($this->bar, '-');
            if ($pos !== false) {
                $this->bar = substr_replace($this->bar, '#', $pos, strlen('-'));
            }
        }
        $this->oldPercentage = $percentage;

        $this->progressSection->overwrite(sprintf('Progress: |%s| %d/%d',$this->bar, $this->counter, $this->total));
    }
}