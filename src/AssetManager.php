<?php namespace Orchestra\Publisher;

use Exception;
use Illuminate\Contracts\Container\Container;
use Orchestra\Publisher\Publishing\AssetPublisher;
use Orchestra\Contracts\Publisher\FilePermissionException;

class AssetManager implements PublisherInterface
{
    /**
     * Application instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * Migrator instance.
     *
     * @var \Orchestra\Publisher\Publishing\AssetPublisher
     */
    protected $publisher;

    /**
     * Construct a new instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $app
     * @param  \Orchestra\Publisher\Publishing\AssetPublisher  $publisher
     */
    public function __construct(Container $app, AssetPublisher $publisher)
    {
        $this->app       = $app;
        $this->publisher = $publisher;
    }

    /**
     * Run migration for an extension or application.
     *
     * @param  string  $name
     * @param  string  $destinationPath
     * @return mixed
     */
    public function publish($name, $destinationPath)
    {
        return $this->publisher->publish($name, $destinationPath);
    }

    /**
     * Migrate extension.
     *
     * @param  string  $name
     * @return mixed
     * @throws \Orchestra\Contracts\Publisher\FilePermissionException
     */
    public function extension($name)
    {
        if (is_null($path = $this->getPathFromExtensionName($name))) {
            return false;
        }

        try {
            return $this->publish($name, $path);
        } catch (Exception $e) {
            throw new FilePermissionException("Unable to publish [{$path}].");
        }
    }

    /**
     * Migrate Orchestra Platform.
     *
     * @return mixed
     * @throws \Orchestra\Contracts\Publisher\FilePermissionException
     */
    public function foundation()
    {
        $path = rtrim($this->app['path.base'], '/').'/vendor/orchestra/foundation/resources/public';

        if (! $this->app['files']->isDirectory($path)) {
            return false;
        }

        try {
            return $this->publish('orchestra/foundation', $path);
        } catch (Exception $e) {
            throw new FilePermissionException("Unable to publish [{$path}].");
        }
    }

    /**
     * Get path from extension name.
     *
     * @param  string  $name
     * @return string|null
     */
    protected function getPathFromExtensionName($name)
    {
        $finder   = $this->app['orchestra.extension.finder'];
        $basePath = rtrim($this->app['orchestra.extension']->option($name, 'path'), '/');
        $basePath = $finder->resolveExtensionPath($basePath);

        $paths = ["{$basePath}/public", "{$basePath}/resources/public"];

        foreach ($paths as $path) {
            if ($this->app['files']->isDirectory($path)) {
                return $path;
            }
        }

        return null;
    }
}
