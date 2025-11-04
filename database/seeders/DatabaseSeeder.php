<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Superadmin User',
            'email' => 'superadmin@arham-ecommerce.tes',
            'password' => bcrypt('password'),
        ]);
        $this->call([
            CatalogSeeder::class,
            CustomerSeeder::class,
            CommerceSeeder::class,
            ContentSeeder::class,
        ]);
    }
}
