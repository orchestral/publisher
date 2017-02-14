<?php

namespace Orchestra\Publisher\Publishing\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Orchestra\Publisher\Publishing\ViewPublisher;

class ViewPublisherTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testPackageViewPublishing()
    {
        $pub = new ViewPublisher($files = m::mock('\Illuminate\Filesystem\Filesystem'), __DIR__);
        $pub->setPackagePath(__DIR__.'/vendor');
        $files->shouldReceive('isDirectory')->once()->with(__DIR__.'/vendor/foo/bar/resources/views')->andReturn(true);
        $files->shouldReceive('isDirectory')->once()->with(__DIR__.'/packages/foo/bar')->andReturn(true);
        $files->shouldReceive('copyDirectory')->once()->with(__DIR__.'/vendor/foo/bar/resources/views', __DIR__.'/packages/foo/bar')->andReturn(true);

        $this->assertTrue($pub->publishPackage('foo/bar'));

        $pub = new ViewPublisher($files2 = m::mock('\Illuminate\Filesystem\Filesystem'), __DIR__);
        $files2->shouldReceive('isDirectory')->once()->with(__DIR__.'/custom-packages/foo/bar/resources/views')->andReturn(false)
            ->shouldReceive('isDirectory')->once()->with(__DIR__.'/custom-packages/foo/bar/views')->andReturn(true);
        $files2->shouldReceive('isDirectory')->once()->with(__DIR__.'/packages/foo/bar')->andReturn(true);
        $files2->shouldReceive('copyDirectory')->once()->with(__DIR__.'/custom-packages/foo/bar/views', __DIR__.'/packages/foo/bar')->andReturn(true);

        $this->assertTrue($pub->publishPackage('foo/bar', __DIR__.'/custom-packages'));
    }
}
