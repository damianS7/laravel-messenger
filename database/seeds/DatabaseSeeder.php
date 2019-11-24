<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //factory('App\User', 50)->create();
        //factory('App\Contact', 8)->create();
        //factory('App\Conversation', 8)->create();
        factory('App\Message', 20)->create();
    }
}
