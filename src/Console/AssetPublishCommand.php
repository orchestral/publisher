<?php namespace Orchestra\Publisher\Console;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Orchestra\Publisher\Publishing\AssetPublisher;

class AssetPublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'publish:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Publish a package's assets to the public directory";

    /**
     * The asset publisher instance.
     *
     * @var \Orchestra\Publisher\Publishing\AssetPublisher
     */
    protected $assets;

    /**
     * Create a new asset publish command instance.
     *
     * @param  \Orchestra\Publisher\Publishing\AssetPublisher  $assets
     * @return void
     */
    public function __construct(AssetPublisher $assets)
    {
        parent::__construct();

        $this->assets = $assets;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        foreach ($this->getPackages() as $package) {
            $this->publishAssets($package);
        }
    }

    /**
     * Publish the assets for a given package name.
     *
     * @param  string  $package
     * @return void
     */
    protected function publishAssets($package)
    {
        if (! is_null($path = $this->getPath())) {
            $this->assets->publish($package, $path);
        } else {
            $this->assets->publishPackage($package);
        }

        $this->output->writeln('<info>Assets published for package:</info> '.$package);
    }

    /**
     * Get the name of the package being published.
     *
     * @return array
     */
    protected function getPackages()
    {
        if (! is_null($package = $this->input->getArgument('package'))) {
            return [$package];
        }

        return $this->findAllAssetPackages();
    }

    /**
     * Find all the asset hosting packages in the system.
     *
     * @return array
     */
    protected function findAllAssetPackages()
    {
        $vendor = $this->laravel['path.base'].'/vendor';
        $paths = Finder::create()->directories()->in($vendor)->name('public')->depth('< 3');

        $packages = [];

        foreach ($paths as $package) {
            $packages[] = $package->getRelativePath();
        }

        return $packages;
    }

    /**
     * Get the specified path to the files.
     *
     * @return string
     */
    protected function getPath()
    {
        $path = $this->input->getOption('path');

        // First we will check for an explicitly specified path from the user. If one
        // exists we will use that as the path to the assets. This allows the free
        // storage of assets wherever is best for this developer's web projects.
        if (! is_null($path)) {
            return $this->laravel['path.base'].'/'.$path;
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['package', InputArgument::OPTIONAL, 'The name of package being published.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['path', null, InputOption::VALUE_OPTIONAL, 'The path to the asset files.', null],
        ];
    }
}
