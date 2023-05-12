<?php

use App\Laravue\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::findOrCreate('view home page', 'api');
        Permission::findOrCreate('manage post', 'api');
    }
}
