<?php

use App\Laravue\Acl;
use App\Laravue\Models\Role;
use Illuminate\Database\Seeder;

class GuestPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $guestRole = Role::findByName(Acl::ROLE_GUEST);
        $guestRole->givePermissionTo(['view home page', 'view menu charts']);
    }
}
