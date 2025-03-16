<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Admin
        $user = User::updateOrCreate(
            [
                'email' => "ennur2828@gmail.com",
            ],
            [
                'name' => "Ernur",
                'surname' => "Hekim",
                'phone' => "+905350435140",
                'password' => bcrypt('12345678'),
            ]
        );
        $user->assignRole(['admin']);
        // Users
        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'email' => $faker->unique()->safeEmail(),
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'phone' => $faker->unique()->phoneNumber(),
                'password' => bcrypt('12345678'),
            ]);
            $user->assignRole(['user']);
        }
    }
}
