<?php

declare(strict_types=1);

namespace Scrumble\Csr\Tests\Feature;

use Scrumble\Csr\Tests\TestCase;
use Illuminate\Support\Facades\File;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

#[Group('17371')]
final class CsrGeneratorTest extends TestCase
{
    #[Test]
    public function it_generates_the_full_controller_service_repository_set(): void
    {
        $this->artisan('csr:gen', ['name' => 'Picture', 'namespace' => 'Holiday'])
            ->expectsConfirmation($this->confirmationFor('controller, service, repository'), 'yes')
            ->assertSuccessful();

        $this->assertGenerated('Http/Controllers/Holiday/PictureController.php', [
            'namespace App\Http\Controllers\Holiday;',
            'class PictureController extends Controller',
            'public function __construct(IPictureService $pictureService)',
        ]);

        $this->assertGenerated('Interfaces/Services/Holiday/IPictureService.php', [
            'interface IPictureService',
        ]);

        $this->assertGenerated('Services/Holiday/PictureService.php', [
            'class PictureService implements IPictureService',
            'public function __construct(IPictureRepository $pictureRepository)',
        ]);

        $this->assertGenerated('Interfaces/Repositories/Holiday/IPictureRepository.php', [
            'interface IPictureRepository',
        ]);

        $this->assertGenerated('Repositories/Holiday/PictureRepository.php', [
            'class PictureRepository implements IPictureRepository',
        ]);
    }

    #[Test]
    public function it_generates_the_optional_model_migration_and_policy(): void
    {
        $this->artisan('csr:gen', [
            'name' => 'Picture',
            'namespace' => 'Holiday',
            '--model' => true,
            '--migration' => true,
            '--policy' => true,
        ])
            ->expectsConfirmation(
                $this->confirmationFor('controller, service, repository, model, migration, policy'),
                'yes',
            )
            ->assertSuccessful();

        $this->assertGenerated('Models/Holiday/Picture.php', [
            'class Picture extends Model',
            "protected \$table = 'picture';",
        ]);

        $this->assertGenerated('Policies/Holiday/PicturePolicy.php', [
            'class PicturePolicy',
            'use HandlesAuthorization;',
        ]);

        $migrations = File::glob($this->migrationsPath() . '/*_create_picture_table.php');
        $this->assertCount(1, $migrations, 'Expected a create_picture_table migration to be generated.');
    }

    #[Test]
    public function it_skips_the_controller_when_the_nc_flag_is_passed(): void
    {
        $this->artisan('csr:gen', ['name' => 'Picture', 'namespace' => 'Holiday', '--nc' => true])
            ->expectsConfirmation($this->confirmationFor('service, repository'), 'yes')
            ->assertSuccessful();

        $this->assertFileDoesNotExist($this->generatedPath('Http/Controllers/Holiday/PictureController.php'));
        $this->assertFileExists($this->generatedPath('Services/Holiday/PictureService.php'));
    }

    #[Test]
    public function it_generates_nothing_when_the_confirmation_is_declined(): void
    {
        $this->artisan('csr:gen', ['name' => 'Picture', 'namespace' => 'Holiday'])
            ->expectsConfirmation($this->confirmationFor('controller, service, repository'), 'no')
            ->expectsOutput('Cancelled')
            ->assertSuccessful();

        $this->assertFileDoesNotExist($this->generatedPath('Http/Controllers/Holiday/PictureController.php'));
        $this->assertFileDoesNotExist($this->generatedPath('Services/Holiday/PictureService.php'));
        $this->assertFileDoesNotExist($this->generatedPath('Repositories/Holiday/PictureRepository.php'));
    }

    /**
     * @param  array<int, string> $expectedContents
     */
    private function assertGenerated(string $relative, array $expectedContents): void
    {
        $path = $this->generatedPath($relative);
        $this->assertFileExists($path);

        $contents = File::get($path);

        collect($expectedContents)->each(
            fn (string $needle) => $this->assertStringContainsString($needle, $contents),
        );
    }

    private function confirmationFor(string $artifacts): string
    {
        return 'Are you sure you want to create: [' . $artifacts . '] with the NAME: Picture and NAMESPACE: Holiday?';
    }
}
