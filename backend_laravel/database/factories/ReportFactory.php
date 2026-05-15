<?php

namespace Database\Factories;

use App\Models\Module27_ReportAnalytics\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    protected $model = Report::class;

    public function definition()
    {
        return [
            'report_name' => $this->faker->sentence(3),
            'report_type' => $this->faker->randomElement(['security_summary', 'vulnerability_report', 'compliance_report', 'incident_report']),
            'format' => $this->faker->randomElement(['pdf', 'json', 'csv']),
            'file_size' => $this->faker->numberBetween(100000, 5000000),
            'generated_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'download_count' => $this->faker->numberBetween(0, 100),
        ];
    }

    public function pdf()
    {
        return $this->state(fn (array $attributes) => ['format' => 'pdf']);
    }
}