<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class usersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("users")->insert([

            //admin user

            [
                "name" => "Admin",
                "username" => "admin",
                "email" => "admin@gmail.com",
                "password" => Hash::make("password"),
                "roles" => "admin",
                "status" => "active"
            ],
            //agent
            [
                "name" => "Agent",
                "username" => "agent",
                "email" => "agent@gmail.com",
                "password" => Hash::make("password"),
                "roles" => "agent",
                "status" => "active"
            ],

            //user
            [
                "name" => "user",
                "username" => "user",
                "email" => "user@gmail.com",
                "password" => Hash::make("password"),
                "roles" => "user",
                "status" => "active"
            ]
        ]);
    }
}
