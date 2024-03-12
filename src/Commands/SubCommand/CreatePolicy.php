<?php

declare(strict_types=1);

namespace Scrumble\Csr\Commands\SubCommand;

use Illuminate\Support\Str;

class CreatePolicy extends CsrGeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csr:policy
                            {name : The name of the policy to be created}
                            {basename : The name of the controller to be created}
                            {namespace? : The namespace and folder to place the item in}';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Policy';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return __DIR__ . '/../stubs/concrete/policy.stub';
    }
}
