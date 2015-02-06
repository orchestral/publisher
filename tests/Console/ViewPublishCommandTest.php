<?php namespace Orchestra\Publisher\Console\TestCase;

use Mockery as m;
use Illuminate\Container\Container;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Orchestra\Publisher\Console\ViewPublishCommand;

class ViewPublishCommandTest extends \PHPUnit_Framework_TestCase
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

        $command = new ViewPublishCommand($pub = m::mock('\Orchestra\Publisher\Publishing\ViewPublisher'));
        $command->setLaravel($laravel);
        $pub->shouldReceive('publishPackage')->once()->with('foo');
        $command->run(new ArrayInput(array('package' => 'foo')), new NullOutput);
    }

}
