<?php

use Mockery as m;
use Illuminate\Container\Container;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Orchestra\Publisher\Console\AssetPublishCommand;

class AssetPublishCommandTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testCommandCallsPublisherWithProperPackageName()
    {
        $command = new AssetPublishCommand($pub = m::mock('\Orchestra\Publisher\Publishing\AssetPublisher'));
        $command->setLaravel(new Container);
        $pub->shouldReceive('alreadyPublished')->andReturn(false);
        $pub->shouldReceive('publishPackage')->once()->with('foo');
        $command->run(new ArrayInput(array('package' => 'foo')), new NullOutput);
    }
}
