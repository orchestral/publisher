<?php namespace Orchestra\Publisher\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Publisher\MigrateManager;

class MigrateManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Container\Container
     */
    protected $app = null;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        $this->app = new Container;
    }

    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        unset($this->app);
        m::close();
    }

    /**
     * Test Orchestra\Publisher\MigrateManager::run() method.
     *
     * @test
     */
    public function testRunMethod()
    {
        $migrator   = m::mock('\Illuminate\Database\Migrations\Migrator');
        $repository = m::mock('\Illuminate\Database\Migrations\DatabaseMigrationRepository');

        $migrator->shouldReceive('getRepository')->once()->andReturn($repository)
            ->shouldReceive('run')->once()->with('/foo/path/migrations')->andReturn(null);
        $repository->shouldReceive('repositoryExists')->once()->andReturn(false)
            ->shouldReceive('createRepository')->once()->andReturn(null);

        $stub = new MigrateManager($this->app, $migrator);
        $stub->run('/foo/path/migrations');
    }

    /**
     * Test Orchestra\Publisher\MigrateManager::extension() method.
     *
     * @test
     */
    public function testExtensionMethod()
    {
        $app = $this->app;

        $app['migrator'] = $migrator = m::mock('\Illuminate\Database\Migrations\Migrator');
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Extension\Factory');
        $app['orchestra.extension.finder'] = $finder = m::mock('\Orchestra\Extension\Finder');

        $repository = m::mock('\Illuminate\Database\Migrations\DatabaseMigrationRepository');

        $extension->shouldReceive('option')->once()->with('foo/bar', 'path')->andReturn('/foo/path/foo/bar/')
            ->shouldReceive('option')->once()->with('foo/bar', 'source-path')->andReturn('/foo/app/foo/bar/')
            ->shouldReceive('option')->once()->with('laravel/framework', 'path')->andReturn('/foo/path/laravel/framework/')
            ->shouldReceive('option')->once()->with('laravel/framework', 'source-path')->andReturn('/foo/path/laravel/framework/');
        $finder->shouldReceive('resolveExtensionPath')->once()->with('/foo/path/foo/bar')->andReturn('/foo/path/foo/bar')
            ->shouldReceive('resolveExtensionPath')->once()->with('/foo/app/foo/bar')->andReturn('/foo/app/foo/bar')
            ->shouldReceive('resolveExtensionPath')->twice()->with('/foo/path/laravel/framework')->andReturn('/foo/path/laravel/framework');
        $files->shouldReceive('isDirectory')->once()->with('/foo/path/foo/bar/database/migrations/')->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with('/foo/path/foo/bar/src/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/foo/app/foo/bar/database/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/foo/app/foo/bar/src/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/foo/path/laravel/framework/database/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/foo/path/laravel/framework/src/migrations/')->andReturn(false);
        $migrator->shouldReceive('getRepository')->once()->andReturn($repository)
            ->shouldReceive('run')->once()->with('/foo/path/foo/bar/database/migrations/')->andReturn(null);
        $repository->shouldReceive('repositoryExists')->once()->andReturn(true)
            ->shouldReceive('createRepository')->never()->andReturn(null);

        $stub = new MigrateManager($app, $migrator);
        $stub->extension('foo/bar');
        $stub->extension('laravel/framework');
    }

    /**
     * Test Orchestra\Publisher\MigrateManager::foundation() method.
     *
     * @test
     */
    public function testFoundationMethod()
    {
        $app = $this->app;

        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['migrator'] = $migrator = m::mock('\Illuminate\Database\Migrations\Migrator');
        $app['path.base'] = '/foo/path/';

        $repository = m::mock('\Illuminate\Database\Migrations\DatabaseMigrationRepository');

        $files->shouldReceive('isDirectory')->once()->with('/foo/path/vendor/orchestra/memory/src/migrations/')->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with('/foo/path/vendor/orchestra/auth/src/migrations/')->andReturn(true);
        $migrator->shouldReceive('getRepository')->twice()->andReturn($repository)
            ->shouldReceive('run')->once()->with('/foo/path/vendor/orchestra/memory/src/migrations/')->andReturn(null)
            ->shouldReceive('run')->once()->with('/foo/path/vendor/orchestra/auth/src/migrations/')->andReturn(null);
        $repository->shouldReceive('repositoryExists')->twice()->andReturn(true)
            ->shouldReceive('createRepository')->never()->andReturn(null);

        $stub = new MigrateManager($app, $migrator);
        $stub->foundation();
    }
}
