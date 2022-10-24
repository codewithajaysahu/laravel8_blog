<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usersCount = max((int)$this->command->ask('How many users would you like?', 20), 1);

        User::factory()
        ->suspended()
        ->create(); 
        User::factory()->count($usersCount)->create();

    //dd(get_class($ajay), get_class($else));
    //$users = $else->concat([$ajay]);
   // dd($users->count());
    }
}
