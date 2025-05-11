<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Command;

class CreateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create
                            {name : The name of the tenant}
                            {--description= : A description for the tenant}
                            {--status=active : Status (active, disabled, suspended)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new tenant';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->argument('name');
        $description = $this->option('description');
        $status = $this->option('status');

        if (!in_array($status, ['active', 'disabled', 'suspended'])) {
            $this->error("Invalid status. Allowed: active, disabled, suspended.");
            return 1;
        }

        $tenant = Tenant::create([
            'name' => $name,
            'description' => $description,
            'status' => $status,
        ]);

        $this->info("Tenant '{$tenant->name}' created with ID {$tenant->id}.");

        $this->call(DatabaseSeeder::class);
        return 0;
    }
}
