<?php

namespace Orchestra\Publisher\TestCase\Console;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Orchestra\Publisher\Console\AssetPublishCommand;

class AssetPublishCommandTest extends TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testCommandCallsPublisherWithProperPackageName()
    {
        $laravel = m::mock('\Illuminate\Contracts\Foundation\Application');
        $laravel->shouldReceive('call')->once()->andReturnUsing(function ($method, $parameters = []) {
            return call_user_func_array($method, $parameters);
        });

        $command = new AssetPublishCommand($pub = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher'));
        $command->setLaravel($laravel);
        $pub->shouldReceive('alreadyPublished')->andReturn(false);
        $pub->shouldReceive('publishPackage')->once()->with('foo');
        $command->run(new ArrayInput(['package' => 'foo']), new NullOutput());
    }
}
