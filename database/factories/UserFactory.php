<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $role = ['admin', 'supervisor', 'user'];
        shuffle($role);

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => rand(1000000000, 9999999999),
            'city' => $this->faker->city(),
            'educational_attainment_id' => null,
            'general_specialization_id' => null,
            'specialization_id' => null,
            'password' => Hash::make('secret'),
            'api_token' => Str::random(60),
            'email_verified_at' => Carbon::now(),
            'role' => $role[0],
            'locale' => 'en_US',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
