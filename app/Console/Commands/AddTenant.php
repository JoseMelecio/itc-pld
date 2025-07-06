<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class AddTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-tenant {name} {description';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a new tenant';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $name = $this->argument('name');
        $description = $this->argument('description');

        Tenant::create([
            'name' => $name,
            'description' => $description,
        ]);

        $this->info("Tenant '{$name}' creado con la descripción: {$description}");
    }
}
