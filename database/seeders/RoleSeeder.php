<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $roles=[
            [
             'name'=>'admin',
            'display_name'=>'Admin',
            'description'=>'Admin Role',
            'created_at'=>now(),
            'updated_at'=>now(),
            ],
            [
                'name'=>'client',
                'display_name'=>'Client',
                'description'=>'Client Role',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name'=>'delivery_manager',
                'display_name'=>'Delivery Manager',
                'description'=>'Delievery Manager Role',
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
         ];
          foreach ($roles as $role){
                Role::create($role);
          }
    }
}
