<?php namespace Orchestra\Publisher;

use Illuminate\Database\Migrations\Migrator;
use Illuminate\Contracts\Container\Container;

class MigrateManager implements PublisherInterface
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
     * @var \Illuminate\Database\Migrations\Migrator
     */
    protected $migrator;

    /**
     * Construct a new instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $app
     * @param  \Illuminate\Database\Migrations\Migrator  $migrator
     */
    public function __construct(Container $app, Migrator $migrator)
    {
        $this->app      = $app;
        $this->migrator = $migrator;
    }

    /**
     * Create migration repository if it's not available.
     *
     * @return void
     */
    protected function createMigrationRepository()
    {
        $repository = $this->migrator->getRepository();

        if (! $repository->repositoryExists()) {
            $repository->createRepository();
        }
    }

    /**
     * Run migration for an extension or application.
     *
     * @param  string  $path
     *
     * @return void
     */
    public function run($path)
    {
        // We need to make sure migration table is available.
        $this->createMigrationRepository();

        $this->migrator->run($path);
    }

    /**
     * Migrate package.
     *
     * @param  string  $name
     *
     * @return void
     */
    public function package($name)
    {
        $basePath   = rtrim($this->app['path.base'], '/');
        $vendorPath = "{$basePath}/vendor";

        $paths = [
            "{$vendorPath}/{$name}/resources/database/migrations/",
            "{$vendorPath}/{$name}/database/migrations/",
            "{$vendorPath}/{$name}/migrations/",
        ];

        foreach ($paths as $path) {
            if ($this->app['files']->isDirectory($path)) {
                $this->run($path);
            }
        }
    }

    /**
     * Migrate extension.
     *
     * @param  string  $name
     *
     * @return void
     */
    public function extension($name)
    {
        $extension  = $this->app['orchestra.extension'];
        $finder     = $this->app['orchestra.extension.finder'];
        $basePath   = $finder->resolveExtensionPath(rtrim($extension->option($name, 'path'), '/'));
        $sourcePath = $finder->resolveExtensionPath(rtrim($extension->option($name, 'source-path'), '/'));

        $paths = [
            "{$basePath}/resources/database/migrations/",
            "{$basePath}/resources/migrations/",
            "{$basePath}/src/migrations/",
        ];

        // We don't execute the same migration twice, this little code
        // compare both folder before appending the paths.
        if ($basePath !== $sourcePath && ! empty($sourcePath)) {
            $paths = array_merge($paths, [
                "{$sourcePath}/resources/database/migrations/",
                "{$sourcePath}/resources/migrations/",
                "{$sourcePath}/src/migrations/",
            ]);
        }

        foreach ($paths as $path) {
            if ($this->app['files']->isDirectory($path)) {
                $this->run($path);
            }
        }
    }

    /**
     * Migrate Orchestra Platform.
     *
     * @return void
     */
    public function foundation()
    {
        $this->package('orchestra/memory');
        $this->package('orchestra/auth');
    }
}
