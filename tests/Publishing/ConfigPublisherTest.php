<?php

namespace Orchestra\Publisher\Publishing\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Publisher\Publishing\ConfigPublisher;

class ConfigPublisherTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testPackageConfigPublishing()
    {
        $pub = new ConfigPublisher($files = m::mock('\Illuminate\Filesystem\Filesystem'), __DIR__);
        $pub->setPackagePath(__DIR__.'/vendor');
        $files->shouldReceive('isDirectory')->once()->with(__DIR__.'/vendor/foo/bar/resources/config')->andReturn(true);
        $files->shouldReceive('isDirectory')->once()->with(__DIR__.'/packages/foo/bar')->andReturn(true);
        $files->shouldReceive('copyDirectory')->once()->with(__DIR__.'/vendor/foo/bar/resources/config', __DIR__.'/packages/foo/bar')->andReturn(true);

        $this->assertTrue($pub->publishPackage('foo/bar'));

        $pub = new ConfigPublisher($files2 = m::mock('\Illuminate\Filesystem\Filesystem'), __DIR__);
        $files2->shouldReceive('isDirectory')->once()->with(__DIR__.'/custom-packages/foo/bar/resources/config')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with(__DIR__.'/custom-packages/foo/bar/config')->andReturn(true);
        $files2->shouldReceive('isDirectory')->once()->with(__DIR__.'/packages/foo/bar')->andReturn(true);
        $files2->shouldReceive('copyDirectory')->once()->with(__DIR__.'/custom-packages/foo/bar/config', __DIR__.'/packages/foo/bar')->andReturn(true);

        $this->assertTrue($pub->publishPackage('foo/bar', __DIR__.'/custom-packages'));
    }
}
