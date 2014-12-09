<?php namespace Orchestra\Publisher\Console\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Orchestra\Publisher\Console\ConfigPublishCommand;

class ConfigPublishCommandTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testCommandCallsPublisherWithProperPackageName()
    {
        $command = new ConfigPublishCommand($pub = m::mock('\Orchestra\Publisher\Publishing\ConfigPublisher'));
        $command->setLaravel(new Container);
        $pub->shouldReceive('alreadyPublished')->andReturn(false);
        $pub->shouldReceive('publishPackage')->once()->with('foo');
        $command->run(new ArrayInput(array('package' => 'foo')), new NullOutput);
    }
}
