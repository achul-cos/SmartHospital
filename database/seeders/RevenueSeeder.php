<?php

namespace Database\Seeders;

use App\Models\Revenue;
use Illuminate\Database\Seeder;

class RevenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Today's revenue
        $today = now();
        $todayRevenue = [
            'date' => $today->toDateString(),
            'total_revenue' => 820000,
            'appointment_count' => 5,
            'consultation_fees' => 400000,
            'medication_fees' => 250000,
            'procedure_fees' => 170000,
            'notes' => 'Pendapatan hari ini dari 5 janji temu',
        ];

        // Yesterday's revenue
        $yesterday = now()->subDay();
        $yesterdayRevenue = [
            'date' => $yesterday->toDateString(),
            'total_revenue' => 680000,
            'appointment_count' => 4,
            'consultation_fees' => 350000,
            'medication_fees' => 200000,
            'procedure_fees' => 130000,
            'notes' => 'Pendapatan kemarin dari 4 janji temu',
        ];

        // This week's revenue (random days)
        $thisWeekRevenue = [];
        for ($i = 2; $i <= 6; $i++) {
            $date = now()->subDays($i);
            $appointmentCount = rand(3, 8);
            $consultationFees = rand(200000, 500000);
            $medicationFees = rand(100000, 300000);
            $procedureFees = rand(50000, 200000);
            $totalRevenue = $consultationFees + $medicationFees + $procedureFees;

            $thisWeekRevenue[] = [
                'date' => $date->toDateString(),
                'total_revenue' => $totalRevenue,
                'appointment_count' => $appointmentCount,
                'consultation_fees' => $consultationFees,
                'medication_fees' => $medicationFees,
                'procedure_fees' => $procedureFees,
                'notes' => "Pendapatan dari {$appointmentCount} janji temu",
            ];
        }

        // Last month's revenue (sample data)
        $lastMonthRevenue = [];
        for ($i = 1; $i <= 30; $i++) {
            $date = now()->subMonth()->addDays($i);
            $appointmentCount = rand(2, 10);
            $consultationFees = rand(150000, 600000);
            $medicationFees = rand(80000, 400000);
            $procedureFees = rand(30000, 250000);
            $totalRevenue = $consultationFees + $medicationFees + $procedureFees;

            $lastMonthRevenue[] = [
                'date' => $date->toDateString(),
                'total_revenue' => $totalRevenue,
                'appointment_count' => $appointmentCount,
                'consultation_fees' => $consultationFees,
                'medication_fees' => $medicationFees,
                'procedure_fees' => $procedureFees,
                'notes' => "Pendapatan dari {$appointmentCount} janji temu",
            ];
        }

        // Combine all revenue records
        $allRevenue = array_merge([$todayRevenue, $yesterdayRevenue], $thisWeekRevenue, $lastMonthRevenue);

        foreach ($allRevenue as $revenueData) {
            if (!Revenue::where('date', $revenueData['date'])->exists()) {
                Revenue::create($revenueData);
            }
        }

        $this->command->info('Revenue data seeded successfully!');
    }
}
