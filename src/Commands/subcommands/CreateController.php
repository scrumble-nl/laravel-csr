<?php

declare(strict_types=1);

namespace Scrumble\Csr\Commands;

use Illuminate\Support\Str;

class CreateController extends CsrGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csr:controller
                            {name : The name of the controller to be created}
                            {basename : The name of the controller to be created}
                            {namespace? : The namespace and folder to place the item in}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../stubs/concrete/controller.stub';
    }
}
