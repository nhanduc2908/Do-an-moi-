<?php

namespace Database\Factories;

use App\Models\Module14_IncidentResponse\Incident;
use Illuminate\Database\Eloquent\Factories\Factory;

class IncidentFactory extends Factory
{
    protected $model = Incident::class;

    public function definition()
    {
        return [
            'incident_code' => 'INC-' . $this->faker->year() . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'severity' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'status' => $this->faker->randomElement(['open', 'investigating', 'resolved', 'closed']),
            'category' => $this->faker->randomElement(['malware', 'phishing', 'unauthorized_access', 'data_breach']),
            'detected_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'reported_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function critical()
    {
        return $this->state(fn (array $attributes) => [
            'severity' => 'critical',
            'status' => 'open'
        ]);
    }

    public function resolved()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'resolved',
            'resolved_at' => $this->faker->dateTimeBetween('-1 week', 'now')
        ]);
    }
}