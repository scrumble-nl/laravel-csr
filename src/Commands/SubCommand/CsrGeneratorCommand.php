<?php

declare(strict_types=1);

namespace Scrumble\Csr\Commands\SubCommand;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

abstract class CsrGeneratorCommand extends GeneratorCommand
{
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new file';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Mixed';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    abstract protected function getStub();

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];
        $replace = $this->buildReplacements($replace);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the controller replacement values.
     *
     * @param  array $replace
     * @return array
     */
    protected function buildReplacements(array $replace)
    {
        $className = $this->validateName($this->argument('name'));
        $namespace = $this->argument('namespace');
        $baseName = $this->argument('basename');

        return array_merge($replace, [
            '{{BaseName}}' => $baseName,
            '{{baseName}}' => lcfirst($baseName),
            '{{Variable}}' => ucfirst(class_basename($className)),
            '{{NamespaceShort}}' => $namespace ? ucfirst(strtolower($namespace)) . '\\' : '',
        ]);
    }

    /**
     * Validate the given name and return
     * the full classname
     *
     * @param  string $name
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function validateName($name)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $name)) {
            throw new InvalidArgumentException('The name "' . $name . '" contains invalid characters.');
        }

        $name = trim(str_replace('/', '\\', $name), '\\');

        if (!Str::startsWith($name, $rootNamespace = $this->laravel->getNamespace())) {
            $name = $rootNamespace . $name;
        }

        return $name;
    }
}
