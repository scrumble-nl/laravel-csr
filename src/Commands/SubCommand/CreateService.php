<?php

declare(strict_types=1);

namespace Scrumble\Csr\Commands\SubCommand;

use Illuminate\Support\Str;

class CreateService extends CsrGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csr:service
                            {name : The name of the service to be created}
                            {basename : The name of the service to be created}
                            {namespace : The namespace and folder to place the item in}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../stubs/concrete/service.stub';
    }
}
