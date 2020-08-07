<?php

declare(strict_types=1);

namespace Scrumble\Csr\Src\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;

class CreateCsr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csr:gen
                            {name : The name for the pattern to be created}
                            {namespace? : The namespace the pattern belongs to}
                            {--nc : Optional disable of controller generation}
                            {--ns : Optional disable of service generation}
                            {--nr : Optional disable of repository generation}
                            {--migration : If the pattern also needs a create migration}
                            {--model : If the pattern also needs a create model}
                            {--policy : If the pattern also needs a create policy}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a full controller/service/repository pattern for the given name and namespace';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller, service, repository, optional model and migration';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $name = ucfirst($this->argument('name'));
        $namespace = ucfirst($this->argument('namespace') ?? '');

        $options = [
            'controller' => !$this->option('nc'),
            'service' => !$this->option('ns'),
            'repository' => !$this->option('nr'),
            'model' => $this->option('model'),
            'migration' => $this->option('migration'),
            'policy' => $this->option('policy'),
        ];

        $enabledOptions = array_filter($options, function ($option) {
            return !!$option;
        });

        if (!$this->confirm('Are you sure you want to create: ['
            . implode(', ', array_keys($enabledOptions))
            . '] with the NAME: ' . $name
            . ($namespace ? ' and NAMESPACE: ' . $namespace : '') . '?')) {
            $this->warn('Cancelled');
            return;
        }

        if (!$this->option('nc')) {
            $this->createController($namespace, $name);
        }

        if (!$this->option('ns')) {
            $this->createService($namespace, $name);
        }

        if (!$this->option('nr')) {
            $this->createRepository($namespace, $name);
        }

        if ($this->option('model')) {
            $this->createModel($namespace, $name);
        }

        if ($this->option('migration')) {
            $this->createMigration();
        }

        if ($this->option('policy')) {
            $this->createPolicy($namespace, $name);
        }

        $this->alert('Generation complete, have fun doing some actual programming!');

        if (!$this->option('ns') || !$this->option('nr')) {
            $this->info('But first, there\'s one more thing to do:');

            if (!$this->option('ns')) {
                $this->warn('> Don\'t forget to bind the service in the AppServiceProvider');
            }

            if (!$this->option('nr')) {
                $this->warn('> Don\'t forget to bind the repository in the AppServiceProvider');
            }
        }
    }

    /**
     * Create the controller
     *
     * @param string $namespace
     * @param string $name
     */
    private function createController(string $namespace, string $name): void
    {
        $this->call('csr:controller', [
            'name' => config('csr.paths.controller') . '/' . $namespace . '/' . $name . 'Controller',
            'basename' => $name,
            'namespace' => $namespace,
        ]);
    }

    /**
     * Create the service
     *
     * @param string $namespace
     * @param string $name
     */
    private function createService(string $namespace, string $name): void
    {
        $this->call('csr:iservice', [
            'name' => config('csr.paths.service_interface') . '/' . $namespace . '/I' . $name . 'Service',
            'basename' => $name,
            'namespace' => $namespace,
        ]);

        $asdf = $this->call('csr:service', [
            'name' => config('csr.paths.service') . '/' . $namespace . '/' . $name . 'Service',
            'basename' => $name,
            'namespace' => $namespace,
        ]);
    }

    /**
     * Create the repository
     *
     * @param string $namespace
     * @param string $name
     */
    private function createRepository(string $namespace, string $name): void
    {
        $this->call('csr:irepository', [
            'name' => config('csr.paths.repository_interface') . '/' . $namespace . '/I' . $name . 'Repository',
            'basename' => $name,
            'namespace' => $namespace,
        ]);

        $this->call('csr:repository', [
            'name' => config('csr.paths.repository') . '/' . $namespace . '/' . $name . 'Repository',
            'basename' => $name,
            'namespace' => $namespace,
        ]);
    }

    /**
     * Create the model
     *
     * @param string $namespace
     * @param string $name
     */
    private function createModel(string $namespace, string $name): void
    {
        $this->call('csr:model', [
            'name' => config('csr.paths.model') . '/' . $namespace . '/' . $name,
            'namespace' => $namespace,
        ]);
    }

    /**
     * Create the migration
     */
    private function createMigration(): void
    {
        $table = Str::singular(Str::snake(class_basename($this->argument('name'))));

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
        ]);
    }

    /**
     * Create the policy
     */
    private function createPolicy(string $namespace, string $name): void
    {
        $this->call('csr:policy', [
            'name' => config('csr.paths.policy') . '/' . $namespace . '/' . $name . 'Policy',
            'basename' => $name,
            'namespace' => $namespace,
        ]);
    }
}
