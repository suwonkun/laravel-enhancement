<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CsvExportHistory>
 */
class CsvExportHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'file_name' => $this->faker->word . '.csv',
            'file_path' => $this->faker->word . '_userList.csv',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
