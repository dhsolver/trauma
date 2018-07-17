<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {
    public function run()
    {
        \App\User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'confirmed' => 1,
            'role' => 'admin',
            'confirmation_code' => md5(microtime() . env('APP_KEY')),
        ]);
    }
}
