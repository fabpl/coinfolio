<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        DB::table('users')->insert([
            'name' => 'Test',
            'email' => 'test@test.local',
            'password' => bcrypt('test'),
            'created_at' => \Carbon\Carbon::now(),
        ]);
    }
}
