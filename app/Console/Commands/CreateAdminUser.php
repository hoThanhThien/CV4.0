<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--name= : Admin name} 
                            {--email= : Admin email} 
                            {--password= : Admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update an admin user securely';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->option('name') ?: $this->ask('Admin Name', 'Admin');
        $email = $this->option('email') ?: $this->ask('Admin Email');

        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $this->error('Invalid email address format.');
            return 1;
        }

        $password = $this->option('password') ?: $this->secret('Admin Password');

        if (empty($password) || strlen($password) < 6) {
            $this->error('Password must be at least 6 characters long.');
            return 1;
        }

        $user = User::where('email', $email)->first();

        if ($user) {
            if ($this->confirm("User with email {$email} already exists. Update password?", true)) {
                $user->update([
                    'name' => $name,
                    'password' => Hash::make($password),
                ]);
                $this->info("Admin user [{$email}] updated successfully!");
                return 0;
            }
            $this->warn('Aborted. No changes made.');
            return 0;
        }

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info("Admin user [{$email}] created successfully!");
        return 0;
    }
}
