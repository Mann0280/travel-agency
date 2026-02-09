<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Agency;
use Illuminate\Support\Facades\DB;

class RoleMigrationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        
        foreach ($users as $user) {
            if ($user->role === 'admin') {
                Admin::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'name' => $user->name,
                        'password' => $user->password,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ]
                );
            } elseif ($user->role === 'agency') {
                $agency = Agency::where('email', $user->email)->first();
                if ($agency) {
                    $agency->update([
                        'password' => $user->password,
                    ]);
                } else {
                    // If agency record doesn't exist but user has agency role,
                    // we might need to create an agency or skip.
                    // For now, we assume agency records were created first as per DatabaseSeeder.
                }
            }
        }
    }
}
