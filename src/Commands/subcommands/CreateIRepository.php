<?php

declare(strict_types=1);

namespace Scrumble\Csr\Commands;

use Illuminate\Support\Str;

class CreateIRepository extends CsrGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csr:irepository
                            {name : The name of the repository interface to be created}
                            {basename : The name of the repository interface to be created}
                            {namespace? : The namespace and folder to place the item in}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository interface';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->argument('model')) {
            return __DIR__ . '/../stubs/abstract/imodelRepository.stub';
        } else {
            return __DIR__ . '/../stubs/abstract/irepository.stub';
        }
    }
}
