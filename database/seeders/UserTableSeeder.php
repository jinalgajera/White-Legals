<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where('email', '=', 'admin@gmail.com')->first() === null) {
            $user = User::create([
                'name'              => 'Admin',
                'email'             => 'admin@gmail.com',
                'password'          => bcrypt('12345678'),
                'phone_number'      => '1234567890',
                'status'            => 1,
                'role'              => 1           
            ]);

            $role = Role::create(['name' => 'Admin']);
     
            $permissions = Permission::pluck('id','id')->all();
    
            $role->syncPermissions($permissions);
        
            $user->assignRole([$role->id]);
        }
    }
}
