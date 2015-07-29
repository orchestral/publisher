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
        $this->app = new Container();
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
            ->shouldReceive('run')->once()->with('/var/www/laravel/migrations')->andReturn(null);
        $repository->shouldReceive('repositoryExists')->once()->andReturn(false)
            ->shouldReceive('createRepository')->once()->andReturn(null);

        $stub = new MigrateManager($this->app, $migrator);
        $stub->run('/var/www/laravel/migrations');
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
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Contracts\Extension\Factory');
        $app['orchestra.extension.finder'] = $finder = m::mock('\Orchestra\Contracts\Extension\Finder');

        $repository = m::mock('\Illuminate\Database\Migrations\DatabaseMigrationRepository');

        $extension->shouldReceive('option')->once()->with('foo/bar', 'path')->andReturn('/var/www/laravel/vendor/foo/bar/')
            ->shouldReceive('option')->once()->with('foo/bar', 'source-path')->andReturn('/var/www/laravel/app/foo/bar/')
            ->shouldReceive('option')->once()->with('laravel/framework', 'path')->andReturn('/var/www/laravel/laravel/framework/')
            ->shouldReceive('option')->once()->with('laravel/framework', 'source-path')->andReturn('/var/www/laravel/laravel/framework/');
        $finder->shouldReceive('resolveExtensionPath')->once()->with('/var/www/laravel/vendor/foo/bar')->andReturn('/var/www/laravel/vendor/foo/bar')
            ->shouldReceive('resolveExtensionPath')->once()->with('/var/www/laravel/app/foo/bar')->andReturn('/var/www/laravel/app/foo/bar')
            ->shouldReceive('resolveExtensionPath')->twice()->with('/var/www/laravel/laravel/framework')->andReturn('/var/www/laravel/laravel/framework');
        $files->shouldReceive('isDirectory')->once()->with('/var/www/laravel/vendor/foo/bar/resources/database/migrations/')->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/vendor/foo/bar/resources/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/vendor/foo/bar/src/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/app/foo/bar/resources/database/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/app/foo/bar/resources/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/app/foo/bar/src/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/laravel/framework/resources/database/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/laravel/framework/resources/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/laravel/framework/src/migrations/')->andReturn(false);
        $migrator->shouldReceive('getRepository')->once()->andReturn($repository)
            ->shouldReceive('run')->once()->with('/var/www/laravel/vendor/foo/bar/resources/database/migrations/')->andReturn(null);
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
        $app = m::mock('\Illuminate\Container\Container')->makePartial();

        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['migrator'] = $migrator = m::mock('\Illuminate\Database\Migrations\Migrator');

        $app->shouldReceive('basePath')->twice()->andReturn('/var/www/laravel');

        $repository = m::mock('\Illuminate\Database\Migrations\DatabaseMigrationRepository');

        $files->shouldReceive('isDirectory')->once()->with('/var/www/laravel/vendor/orchestra/memory/resources/database/migrations/')->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/vendor/orchestra/memory/database/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/vendor/orchestra/memory/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/vendor/orchestra/auth/resources/database/migrations/')->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/vendor/orchestra/auth/database/migrations/')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('/var/www/laravel/vendor/orchestra/auth/migrations/')->andReturn(false);
        $migrator->shouldReceive('getRepository')->twice()->andReturn($repository)
            ->shouldReceive('run')->once()->with('/var/www/laravel/vendor/orchestra/memory/resources/database/migrations/')->andReturn(null)
            ->shouldReceive('run')->once()->with('/var/www/laravel/vendor/orchestra/auth/resources/database/migrations/')->andReturn(null);
        $repository->shouldReceive('repositoryExists')->twice()->andReturn(true)
            ->shouldReceive('createRepository')->never()->andReturn(null);

        $stub = new MigrateManager($app, $migrator);
        $stub->foundation();
    }
}
