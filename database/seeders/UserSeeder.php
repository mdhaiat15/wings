<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'test';
        $user->email = 'test@test.web';
        $user->password = Hash::make('test');
        $user->save();

        $user = new User();
        $user->name = 'pengguna';
        $user->email = 'pengguna@test.web';
        $user->password = Hash::make('test');
        $user->save();
    }
}
