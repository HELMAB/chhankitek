<?php

declare(strict_types=1);

namespace Asorasoft\Chhankitek\Commands;

use Asorasoft\Chhankitek\Traits\HasChhankitek;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;

class VisakBocheaCheckCommand extends Command
{
    use HasChhankitek;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chhankitek:check-visak-bochea {date? : The date to check (YYYY-MM-DD format)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if a date is Visak Bochea';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $dateString = $this->argument('date') ?? date('Y-m-d');
        $this->info("Checking Visak Bochea for date: $dateString");
        
        $date = CarbonImmutable::parse($dateString)->setTimezone('Asia/Phnom_Penh');
        $toLunarDate = $this->chhankiteck($date);

        $this->output->writeln("<fg=yellow>🌙 Lunar Date Information:</>");
        $this->output->writeln("Date: " . $date->format('Y-m-d'));
        $this->output->writeln("Formatted: " . $toLunarDate->toString());
        $this->output->writeln("");
        
        $testsPassed = 0;
        $totalTests = 6;
        
        // Check day of week
        if ($toLunarDate->getDayOfWeek() === 'អាទិត្យ') {
            $this->info("getDayOfWeek/Passed");
            $testsPassed++;
        } else {
            $this->error("getDayOfWeek/Failed: " . $toLunarDate->getDayOfWeek());
        }

        // Check lunar day
        if ($toLunarDate->getLunarDay() === '១៥កើត') {
            $this->info("getLunarDay/Passed");
            $testsPassed++;
        } else {
            $this->error("getLunarDay/Failed: " . $toLunarDate->getLunarDay());
        }

        // Check lunar month
        if ($toLunarDate->getLunarMonth() === 'ពិសាខ') {
            $this->info("getLunarMonth/Passed");
            $testsPassed++;
        } else {
            $this->error("getLunarMonth/Failed: " . $toLunarDate->getLunarMonth());
        }

        // Check lunar zodiac
        if ($toLunarDate->getLunarZodiac() === 'ម្សាញ់') {
            $this->info("getLunarZodiac/Passed");
            $testsPassed++;
        } else {
            $this->error("getLunarZodiac/Failed: " . $toLunarDate->getLunarZodiac());
        }

        // Check lunar era
        if ($toLunarDate->getLunarEra() === 'សប្តស័ក') {
            $this->info("getLunarEra/Passed");
            $testsPassed++;
        } else {
            $this->error("getLunarEra/Failed: " . $toLunarDate->getLunarEra());
        }

        // Check lunar year
        if ($toLunarDate->getLunarYear() === '២៥៦៨') {
            $this->info("getLunarYear/Passed");
            $testsPassed++;
        } else {
            $this->error("getLunarYear/Failed: " . $toLunarDate->getLunarYear());
        }
        
        $this->output->writeln("");
        if ($testsPassed === $totalTests) {
            $this->info("✅ All tests passed! This date is Visak Bochea.");
        } else {
            $this->warn("⚠️ Only $testsPassed/$totalTests tests passed. This date may not be Visak Bochea.");
        }
        
        return $testsPassed === $totalTests ? 0 : 1;
    }
}
