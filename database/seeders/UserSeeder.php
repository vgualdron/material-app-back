<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "admin",
            "document_number" => "admin",
            "phone" => "000000",
            "yard" => null,
            "password" => Hash::make('4dm1n')
        ])->assignRole('admin');
    }
}
