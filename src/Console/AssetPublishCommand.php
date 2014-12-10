<?php namespace Orchestra\Publisher\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Orchestra\Publisher\Publishing\AssetPublisher;
use Orchestra\Publisher\Console\Traits\PublishingPathTrait;

class AssetPublishCommand extends Command
{
    use PublishingPathTrait;

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
        $package = $this->input->getArgument('package');

        $this->publishAssets($package);
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
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['package', InputArgument::REQUIRED, 'The name of package being published.'],
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
