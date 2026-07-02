<?php

declare(strict_types=1);

namespace Scrumble\Csr\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Scrumble\Csr\CsrServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * Files already present in the testbench skeleton before a test runs; used
     * to strip everything the generator commands add during the test.
     *
     * @var array<int, string>
     */
    private array $preexistingFiles;

    protected function setUp(): void
    {
        parent::setUp();

        File::ensureDirectoryExists($this->migrationsPath());
        $this->preexistingFiles = $this->skeletonFiles()->all();
    }

    protected function tearDown(): void
    {
        $this->skeletonFiles()
            ->reject(fn (string $file) => in_array($file, $this->preexistingFiles, true))
            ->each(fn (string $file) => File::delete($file));

        parent::tearDown();
    }

    /**
     * @param  \Illuminate\Foundation\Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [CsrServiceProvider::class];
    }

    /**
     * The package only publishes its config, so mirror a published install by
     * loading the defaults into the test environment.
     *
     * @param  \Illuminate\Foundation\Application $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('csr', require __DIR__ . '/../config/csr.php');
    }

    /**
     * Absolute path to a class generated under the application directory.
     */
    protected function generatedPath(string $relative): string
    {
        return $this->app->path($relative);
    }

    protected function migrationsPath(): string
    {
        return $this->app->databasePath('migrations');
    }

    /**
     * @return Collection<int, string>
     */
    private function skeletonFiles(): Collection
    {
        return collect(File::allFiles([$this->app->path(), $this->migrationsPath()]))
            ->map(fn ($file) => $file->getPathname());
    }
}
