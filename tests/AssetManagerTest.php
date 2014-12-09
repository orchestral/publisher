<?php namespace Orchestra\Publisher\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Orchestra\Publisher\AssetManager;

class AssetManagerTest extends \PHPUnit_Framework_TestCase
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
     * Test Orchestra\Publisher\AssetManager::publish() method.
     *
     * @test
     */
    public function testPublishMethod()
    {
        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');
        $publisher->shouldReceive('publish')->once()->with('foo', 'bar')->andReturn(true);

        $stub = new AssetManager($this->app, $publisher);
        $this->assertTrue($stub->publish('foo', 'bar'));
    }

    /**
     * Test Orchestra\Publisher\AssetManager::extension() method.
     *
     * @test
     */
    public function testExtensionMethod()
    {
        $app = $this->app;
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Extension\Factory');
        $app['orchestra.extension.finder'] = $finder = m::mock('\Orchestra\Extension\Finder');

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $files->shouldReceive('isDirectory')->once()->with('bar/resources/public')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('bar/public')->andReturn(true)
            ->shouldReceive('isDirectory')->once()->with('foobar/public')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with('foobar/resources/public')->andReturn(false);
        $extension->shouldReceive('option')->once()->with('foo', 'path')->andReturn('bar')
            ->shouldReceive('option')->once()->with('foobar', 'path')->andReturn('foobar');
        $finder->shouldReceive('resolveExtensionPath')->once()->with('bar')->andReturn('bar')
            ->shouldReceive('resolveExtensionPath')->once()->with('foobar')->andReturn('foobar');
        $publisher->shouldReceive('publish')->once()->with('foo', 'bar/public')->andReturn(true);

        $stub = new AssetManager($app, $publisher);
        $this->assertTrue($stub->extension('foo'));
        $this->assertFalse($stub->extension('foobar'));
    }

    /**
     * Test Orchestra\Publisher\AssetManager::extension() method
     * throws exception.
     *
     * @expectedException \Orchestra\Contracts\Publisher\FilePermissionException
     */
    public function testExtensionMethodThrowsException()
    {
        $app = $this->app;
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['orchestra.extension'] = $extension = m::mock('\Orchestra\Extension\Factory');
        $app['orchestra.extension.finder'] = $finder = m::mock('\Orchestra\Extension\Finder');

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
        $app = $this->app;
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['path.base'] = '/foo/path/';

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $files->shouldReceive('isDirectory')->once()
            ->with('/foo/path/vendor/orchestra/foundation/resources/public')->andReturn(true);
        $publisher->shouldReceive('publish')->once()
            ->with('orchestra/foundation', '/foo/path/vendor/orchestra/foundation/resources/public')->andReturn(true);

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
        $app = $this->app;
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['path.base'] = '/foo/path/';

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $files->shouldReceive('isDirectory')->once()
            ->with('/foo/path/vendor/orchestra/foundation/resources/public')->andReturn(false);

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
        $app = $this->app;
        $app['files'] = $files = m::mock('\Illuminate\Filesystem\Filesystem');
        $app['path.base'] = '/foo/path/';

        $publisher = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher');

        $files->shouldReceive('isDirectory')->once()
            ->with('/foo/path/vendor/orchestra/foundation/resources/public')->andReturn(true);
        $publisher->shouldReceive('publish')->once()
            ->with('orchestra/foundation', '/foo/path/vendor/orchestra/foundation/resources/public')->andThrow('Exception');

        $stub = new AssetManager($app, $publisher);
        $stub->foundation();
    }
}
