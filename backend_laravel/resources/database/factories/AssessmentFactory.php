<?php

namespace Database\Factories;

use App\Models\Module15_RiskAssessment\RiskAssessment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssessmentFactory extends Factory
{
    protected $model = RiskAssessment::class;

    public function definition()
    {
        return [
            'risk_name' => $this->faker->sentence(3),
            'risk_description' => $this->faker->paragraph(),
            'risk_level' => $this->faker->randomElement(['Low', 'Medium', 'High', 'Critical']),
            'inherent_likelihood' => $this->faker->numberBetween(1, 5),
            'inherent_impact' => $this->faker->numberBetween(1, 5),
            'inherent_risk_score' => $this->faker->numberBetween(1, 25),
            'assessment_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'status' => $this->faker->randomElement(['active', 'reviewed', 'archived']),
        ];
    }

    public function critical()
    {
        return $this->state(fn (array $attributes) => [
            'risk_level' => 'Critical',
            'inherent_likelihood' => 5,
            'inherent_impact' => 5,
            'inherent_risk_score' => 25
        ]);
    }
}