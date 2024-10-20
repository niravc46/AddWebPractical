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
         // Create Admin
         $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('Admin');

        // Create authors
        $authors = [
            [
                'name' => 'Test Author 1',
                'email' => 'author1@author.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Test Author 2',
                'email' => 'author2@author.com',
                'password' => bcrypt('password'),
            ],
            [
                'name' => 'Test Author 3',
                'email' => 'author3@author.com',
                'password' => bcrypt('password'),
            ],
        ];

        foreach ($authors as $authorData) {
            $author = User::create($authorData);
            $author->assignRole('Author');
        }
    }
}
