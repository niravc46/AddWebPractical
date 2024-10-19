<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Admin user
         $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('Admin');


         // Test Autor
         $author = User::create([
            'name' => 'Test Author',
            'email' => 'author@author.com',
            'password' => bcrypt('password'),
        ]);

        // Assign Role
        $author->assignRole('Author');
    }
}
