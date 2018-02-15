<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create(['email' => 'pankaj@acadgild.com', 'name' => 'Pankaj']);
        factory(User::class)->create(['email' => 'aakar@acadgild.com', 'name' => 'aakar']);
        factory(User::class)->create(['email' => 'rahul@acadgild.com', 'name' => 'rahul']);
        factory(User::class)->create(['email' => 'shabeena@acadgild.com', 'name' => 'shabeena']);
        factory(User::class)->create(['email' => 'vikalp@acadgild.com', 'name' => 'vikalp']);
    }
}
