<?php

namespace Ft6k\ComposerDotenv;

use Composer\IO\IOInterface;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class Processor
{
    /** @var  IOInterface */
    protected $io;

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
    {}

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
