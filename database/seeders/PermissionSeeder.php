<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = ['settings','reports','payments','dashboard','profiles', 'jobs', 'pricebook'];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
?>
