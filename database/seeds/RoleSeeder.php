<?php

use Illuminate\Database\Seeder;
use App\Laravue\Models\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Role::findOrCreate('guest','api');
    }
}
