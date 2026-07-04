<?php

namespace Elmasry\StarterKit\Commands;

use Illuminate\Console\Command;
use Elmasry\StarterKit\Models\User;

class CreateFilamentUserCommand extends Command
{
    protected $signature = 'starter-kit:make-user
        {--name= : Name of the user}
        {--email= : Email of the user}
        {--password= : Password for the user}';

    protected $description = 'Create a new Filament admin user with super_admin role';

    public function handle(): int
    {
        $name = $this->option('name') ?? $this->ask('Name');
        $email = $this->option('email') ?? $this->ask('Email');
        $password = $this->option('password') ?? $this->secret('Password');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'locale' => 'en',
            'is_active' => true,
        ]);

        $user->assignRole('super_admin');

        $this->info("User {$user->email} created successfully with super_admin role.");

        return Command::SUCCESS;
    }
}
