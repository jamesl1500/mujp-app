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
        $users = [
            [
                'first_name' => 'Mike',
                'last_name' => 'Bright',
                'email' => 'mike@markamono.com',
                'password' => Hash::make('123123123'),
                'role_key' => 'admin'
            ],
            [
                'first_name' => 'Brian',
                'last_name' => 'Sahan',
                'email' => 'brian@markamono.com',
                'password' => Hash::make('123123123'),
                'role_key' => 'member'
            ],
            [
                'first_name' => 'Serkan',
                'last_name' => 'Yıldırımturk',
                'email' => 'serkan@markamono.com',
                'password' => Hash::make('123123123'),
                'role_key' => 'data-entry'
            ]
        ];

        foreach ($users as $user ){
            User::create($user);
        }
    }
}
