<?php

namespace Orchestra\Publisher\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Orchestra\Publisher\AssetManager;

class AssetManagerTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    public function tearDown()
    {
        m::close();
    }

    /**
     * Test Orchestra\Publisher\AssetManager::publish() method.
     *
     * @test
     */
    public function testPublishMethod()
    {
        $app = new Container();
        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');
        $publisher->shouldReceive('publish')->once()->with('foo', 'bar')->andReturn(true);

        $stub = new AssetManager($app, $publisher);
        $this->assertTrue($stub->publish('foo', 'bar'));
    }

    /**
     * Test Orchestra\Publisher\AssetManager::extension() method.
     *
     * @test
     */
    public function testExtensionMethod()
    {
        $app = new Container();
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Contracts\Extension\Factory');
        $app['orchestra.extension.finder'] = $finder = m::mock('\Orchestra\Contracts\Extension\Finder');

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $files->shouldReceive('isDirectory')->once()->with('var/www/laravel/vendor/foo/bar/resources/public')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('var/www/laravel/vendor/foo/bar/public')->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with('foobar/public')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('foobar/resources/public')->andReturn(false);
        $extension->shouldReceive('option')->once()->with('foo', 'path')->andReturn('var/www/laravel/vendor/foo/bar')
            ->shouldReceive('option')->once()->with('foobar', 'path')->andReturn('foobar');
        $finder->shouldReceive('resolveExtensionPath')->once()->with('var/www/laravel/vendor/foo/bar')->andReturn('var/www/laravel/vendor/foo/bar')
            ->shouldReceive('resolveExtensionPath')->once()->with('foobar')->andReturn('foobar');
        $publisher->shouldReceive('publish')->once()->with('foo', 'var/www/laravel/vendor/foo/bar/public')->andReturn(true);

        $stub = new AssetManager($app, $publisher);
        $this->assertTrue($stub->extension('foo'));
        $this->assertFalse($stub->extension('foobar'));
    }

    /**
     * Test Orchestra\Publisher\AssetManager::extension() method.
     *
     * @test
     */
    public function testExtensionMethodAsApplication()
    {
        $app = m::mock('\Illuminate\Container\Container')->makePartial();
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Contracts\Extension\Factory');
        $app['orchestra.extension.finder'] = $finder = m::mock('\Orchestra\Contracts\Extension\Finder');

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $app->shouldReceive('basePath')->once()->andReturn('/var/www/laravel');

        $files->shouldReceive('isDirectory')->once()->with('/var/www/laravel/resources/public')->andReturn(true);
        $publisher->shouldReceive('publish')->once()->with('app', '/var/www/laravel/resources/public')->andReturn(true);

        $stub = new AssetManager($app, $publisher);
        $this->assertTrue($stub->extension('app'));
    }

    /**
     * Test Orchestra\Publisher\AssetManager::extension() method
     * throws exception.
     *
     * @expectedException \Orchestra\Contracts\Publisher\FilePermissionException
     */
    public function testExtensionMethodThrowsException()
    {
        $app = new Container();
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Contracts\Extension\Factory');
        $app['orchestra.extension.finder'] = $finder = m::mock('\Orchestra\Contracts\Extension\Finder');

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $files->shouldReceive('isDirectory')->once()->with('bar/resources/public')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('bar/public')->andReturn(true);
        $extension->shouldReceive('option')->once()->with('foo', 'path')->andReturn('bar');
        $finder->shouldReceive('resolveExtensionPath')->once()->with("bar")->andReturn('bar');
        $publisher->shouldReceive('publish')->once()->with('foo', 'bar/public')->andThrow('\Exception');

        $stub = new AssetManager($app, $publisher);
        $this->assertFalse($stub->extension('foo'));
    }

    /**
     * Test Orchestra\Publisher\AssetManager::foundation() method.
     *
     * @test
     */
    public function testFoundationMethod()
    {
        $app = m::mock('\Illuminate\Container\Container, \Illuminate\Contracts\Foundation\Application');
        $files = m::mock('\Illuminate\Filesystem\Filesystem');

        $app->shouldReceive('basePath')->once()->andReturn('/var/www/laravel')
            ->shouldReceive('make')->once()->with('files')->andReturn($files);

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $files->shouldReceive('isDirectory')->once()
            ->with('/var/www/laravel/vendor/orchestra/foundation/resources/public')->andReturn(true);
        $publisher->shouldReceive('publish')->once()
            ->with('orchestra/foundation', '/var/www/laravel/vendor/orchestra/foundation/resources/public')->andReturn(true);

        $stub = new AssetManager($app, $publisher);
        $this->assertTrue($stub->foundation());
    }

    /**
     * Test Orchestra\Publisher\AssetManager::foundation() method
     * when public directory does not exists.
     *
     * @test
     */
    public function testFoundationMethodWhenPublicDirectoryDoesNotExists()
    {
        $app = m::mock('\Illuminate\Container\Container, \Illuminate\Contracts\Foundation\Application');
        $files = m::mock('\Illuminate\Filesystem\Filesystem');

        $app->shouldReceive('basePath')->once()->andReturn('/var/www/laravel')
            ->shouldReceive('make')->once()->with('files')->andReturn($files);

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $files->shouldReceive('isDirectory')->once()
            ->with('/var/www/laravel/vendor/orchestra/foundation/resources/public')->andReturn(false);

        $stub = new AssetManager($app, $publisher);
        $this->assertFalse($stub->foundation());
    }

    /**
     * Test Orchestra\Publisher\AssetManager::foundation() method
     * throws an exception.
     *
     * @expectedException \Orchestra\Contracts\Publisher\FilePermissionException
     */
    public function testFoundationMethodThrowsException()
    {
        $app = m::mock('\Illuminate\Container\Container, \Illuminate\Contracts\Foundation\Application');
        $files = m::mock('\Illuminate\Filesystem\Filesystem');

        $app->shouldReceive('basePath')->once()->andReturn('/var/www/laravel/')
            ->shouldReceive('make')->once()->with('files')->andReturn($files);

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $files->shouldReceive('isDirectory')->once()
            ->with('/var/www/laravel/vendor/orchestra/foundation/resources/public')->andReturn(true);
        $publisher->shouldReceive('publish')->once()
            ->with('orchestra/foundation', '/var/www/laravel/vendor/orchestra/foundation/resources/public')->andThrow('Exception');

        $stub = new AssetManager($app, $publisher);
        $stub->foundation();
    }
}
