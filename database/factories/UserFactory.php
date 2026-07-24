<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'               => fake()->name(),
            'email'              => fake()->unique()->safeEmail(),
            'email_verified_at'  => now(),
            'password'           => static::$password ??= Hash::make('password'),
            'remember_token'     => Str::random(10),
            'role_id'            => User::USER_ROLE_ID,
            'total_xp'           => 0,
            'study_streak'       => 0,
            'last_study_date'    => null,
            'total_study_time'   => 0,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * 管理者ユーザーとして生成
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => User::ADMIN_ROLE_ID,
        ]);
    }

    /**
     * 一定の学習実績があるユーザーとして生成
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_xp'         => fake()->numberBetween(200, 3000),
            'study_streak'     => fake()->numberBetween(1, 30),
            'last_study_date'  => now()->subDays(fake()->numberBetween(0, 2)),
            'total_study_time' => fake()->numberBetween(600, 36000),
        ]);
    }
}
