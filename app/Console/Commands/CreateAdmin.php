<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature   = 'admin:create';
    protected $description = 'Create a new admin user';

    public function handle(): void
    {
        $name     = $this->ask('Admin name');
        $email    = $this->ask('Admin email');
        $password = $this->secret('Admin password');
        $role     = $this->choice('Role', ['super_admin', 'admin', 'manager'], 1);

        Admin::create([
            'name'     => $name,
            'email'    => $email,
            'password' => Hash::make($password),
            'role'     => $role,
        ]);

        $this->info("Admin '{$name}' created successfully!");
    }
}