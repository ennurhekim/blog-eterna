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

        // Admin Kullanıcı
        $user = User::updateOrCreate(
            [
                'email' => 'ennur2828@gmail.com',
            ],
            [
                'name' => 'Ernur',
                'surname' => 'Hekim',
                'phone' => '+905350435140',
                'password' => bcrypt('12345678'),
            ]
        );
        $user->assignRole('admin');
        // Admin Kullanıcı
        $user = User::updateOrCreate(
            [
                'email' => 'writer@test.com',
            ],
            [
                'name' => 'test',
                'surname' => 'test',
                'phone' => '+905350435141',
                'password' => bcrypt('12345678'),
            ]
        );
        $user->assignRole('writer');
        // Rastgele 10 kullanıcı oluştur
        $user = User::updateOrCreate(
            [
                'email' => 'user@test.com',
            ],
            [
                'name' => 'user',
                'surname' => 'user',
                'phone' => '+905350435142',
                'password' => bcrypt('12345678'),
            ]
        );
        $user->assignRole('user');
        // Rastgele 10 kullanıcı oluştur
        for ($i = 0; $i < 10; $i++) {
            $user = User::create([
                'email' => $faker->unique()->safeEmail(),
                'name' => $faker->firstName(),
                'surname' => $faker->lastName(),
                'phone' => $faker->unique()->phoneNumber(),
                'password' => bcrypt('12345678'),
            ]);
            $user->assignRole('user');
        }
    }
}
