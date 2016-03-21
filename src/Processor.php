<?php

namespace Ft6k\ComposerDotenv;

use Composer\IO\IOInterface;
use Ft6k\ComposerDotenv\Exception\InvalidArgumentException;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class Processor
{
    /** @var  IOInterface */
    protected $io;

    /** @var  string  Full path to the ignored dotenv file. */
    protected $file;

    /** @var  string  Full path to the distributed dotenv template. */
    protected $distFile;

    /**
     * Processor constructor.
     *
     * @param  IOInterface  $io
     * @param  array        $config
     */
    public function __construct(IOInterface $io, array $config)
    {
        $this->io = $io;
        $this->processConfig($config);
    }

    /**
     * Process the script configuration.
     *
     * @param  array  $config
     */
    protected function processConfig(array $config)
    {
        // Make sure keys exist first
        if (!isset($config['file'])) {
            throw new InvalidArgumentException('No dotenv file configured.');
        } elseif (!isset($config['dist-file'])) {
            throw new InvalidArgumentException('No dotenv distribution file configured.');
        }

        $cwd = getcwd();
        $filePath = $cwd .'/'. $config['file'];
        $distFilePath = $cwd .'/'. $config['dist-file'];

        // Make sure the dist-file exists.
        if (!is_file($distFilePath)) {
            throw new InvalidArgumentException(sprintf(
                'The specified dist-file "%s" does not exist.',
                $distFilePath
            ));
        }

        // Set paths
        $this->file = $filePath;
        $this->distFile = $distFilePath;
    }

    /**
     * Run the dotenv processor.
     *
     * @return  int
     */
    public function run()
    {
        return 0;
    }
}
