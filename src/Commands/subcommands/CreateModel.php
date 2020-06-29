<?php

declare(strict_types=1);

namespace Scrumble\Csr\Commands;

use Illuminate\Support\Str;

class CreateModel extends CsrGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csr:model
                            {name : The name of the model to be created}
                            {namespace? : The namespace and folder to place the item in}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../stubs/concrete/model.stub';
    }

    /**
     * Build the model replacement values.
     *
     * @param  array $replace
     * @return array
     */
    protected function buildReplacements(array $replace)
    {
        $className = $this->validateName($this->argument('name'));

        return array_merge($replace, [
            'DummyClass' => class_basename($className),
            '{{variable}}' => lcfirst(class_basename($className)),
        ]);
    }
}
