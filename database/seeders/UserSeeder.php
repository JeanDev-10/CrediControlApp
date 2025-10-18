<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $admin_role = Role::create(['name' => 'admin']);
        $client_role = Role::create(['name' => 'client']);
        Permission::firstOrCreate(['name' => 'user.create']);
        Permission::firstOrCreate(['name' => 'user.show']);
        Permission::firstOrCreate(['name' => 'user.update']);
        Permission::firstOrCreate(['name' => 'user.index']);
        Permission::firstOrCreate(['name' => 'user.toogleIsActive']);
        Permission::firstOrCreate(['name' => 'user.exportToPdf']);
        $admin = User::create([
            'name' => 'Jean Pierre',
            'lastname' => 'Rodríguez Zambrano',
            'email' => 'admin@hotmail.com',
            'password' => bcrypt('Jean1234@.'),
        ]);
        $admin->assignRole('admin');
        $admin_role->syncPermissions(Permission::all());
        User::create([
            'name' => 'Jean Pierre',
            'lastname' => 'Rodríguez',
            'email' => 'jean@hotmail.com',
            'password' => bcrypt('jean1234'),
        ])->assignRole($client_role);
        User::create([
            'name' => 'test user 2',
            'lastname' => 'apellido',
            'email' => 'test@hotmail.com',
            'password' => bcrypt('password1234'),
        ])->assignRole($client_role);
    }
}
