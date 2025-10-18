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
        Permission::firstOrCreate(['name' => 'users.index']);
        Permission::firstOrCreate(['name' => 'users.show']);
        Permission::firstOrCreate(['name' => 'users.create']);
        Permission::firstOrCreate(['name' => 'users.update']);
        Permission::firstOrCreate(['name' => 'users.destroy']);
        Permission::firstOrCreate(['name' => 'users.toogleIsActive']);
        Permission::firstOrCreate(['name' => 'users.exportToPdf']);
        $admin = User::create([
            'name' => 'Jean Pierre',
            'lastname' => 'Rodríguez Zambrano',
            'email' => 'admin@hotmail.com',
            'password' => bcrypt('Jean1234@.'),
        ]);
        $admin_role->syncPermissions(Permission::all());
        $admin->assignRole('admin');
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
         // Crear usuarios con la fábrica y asignarles roles
        User::factory(20)->create()->each(function ($user) {
            // Asignar el rol 'client' a cada usuario creado
            $user->assignRole('client');
        });
    }
}
