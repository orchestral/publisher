<?php namespace Orchestra\Publisher;

use Illuminate\Support\ServiceProvider;
use Orchestra\Publisher\Publishing\ViewPublisher;
use Orchestra\Publisher\Publishing\AssetPublisher;
use Orchestra\Publisher\Console\ViewPublishCommand;
use Orchestra\Publisher\Console\AssetPublishCommand;

class CommandServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAssetPublisher();

        $this->registerViewPublisher();

        $this->commands([
            'command.asset.publish',
            'command.view.publish',
        ]);
    }

    /**
     * Register the asset publisher service and command.
     *
     * @return void
     */
    protected function registerAssetPublisher()
    {
        $this->registerAssetPublishCommand();

        $this->app->singleton('asset.publisher', function ($app) {
            $publicPath = $app['path.public'];

            // The asset "publisher" is responsible for moving package's assets into the
            // web accessible public directory of an application so they can actually
            // be served to the browser. Otherwise, they would be locked in vendor.
            $publisher = new AssetPublisher($app['files'], $publicPath);

            $publisher->setPackagePath($app['path.base'].'/vendor');

            return $publisher;
        });
    }

    /**
     * Register the asset publish console command.
     *
     * @return void
     */
    protected function registerAssetPublishCommand()
    {
        $this->app->singleton('command.asset.publish', function ($app) {
            return new AssetPublishCommand($app['asset.publisher']);
        });
    }

    /**
     * Register the view publisher class and command.
     *
     * @return void
     */
    protected function registerViewPublisher()
    {
        $this->registerViewPublishCommand();

        $this->app->singleton('view.publisher', function ($app) {
            $viewPath = $app['path.base'].'/resources/views';

            // Once we have created the view publisher, we will set the default packages
            // path on this object so that it knows where to find all of the packages
            // that are installed for the application and can move them to the app.
            $publisher = new ViewPublisher($app['files'], $viewPath);

            $publisher->setPackagePath($app['path.base'].'/vendor');

            return $publisher;
        });
    }

    /**
     * Register the view publish console command.
     *
     * @return void
     */
    protected function registerViewPublishCommand()
    {
        $this->app->singleton('command.view.publish', function ($app) {
            return new ViewPublishCommand($app['view.publisher']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'asset.publisher',
            'command.asset.publish',
            'view.publisher',
            'command.view.publish',
        ];
    }
}
