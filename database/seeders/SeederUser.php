<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SeederUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Pelanggan Pertama',
            'username' => 'pelanggan01',
            'email' => 'pelanggan01@gmail.com',
            'password' => Hash::make('password'),
        ]);
    }
}
