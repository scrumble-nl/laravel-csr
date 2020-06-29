<?php

declare(strict_types=1);

namespace Scrumble\Csr\Commands;

use Illuminate\Support\Str;

class CreateIService extends CsrGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csr:iservice
                            {name : The name of the service interface to be created}
                            {basename : The name of the service interface to be created}
                            {namespace? : The namespace and folder to place the item in}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Service interface';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../stubs/abstract/iservice.stub';
    }
}
