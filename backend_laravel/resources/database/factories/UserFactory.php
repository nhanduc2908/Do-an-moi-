<?php

namespace Database\Factories;

use App\Models\Module01_IAM\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'avatar' => $this->faker->imageUrl(100, 100, 'people'),
            'department' => $this->faker->randomElement(['IT', 'Security', 'Compliance', 'HR', 'Finance']),
            'position' => $this->faker->jobTitle(),
            'phone' => $this->faker->phoneNumber(),
            'status' => 'active',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified()
    {
        return $this->state(fn (array $attributes) => ['email_verified_at' => null]);
    }

    public function admin()
    {
        return $this->state(fn (array $attributes) => ['email' => 'admin@security.com']);
    }

    public function suspended()
    {
        return $this->state(fn (array $attributes) => ['status' => 'suspended']);
    }
}