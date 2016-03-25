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

    /** @var  array  The processor configuration. */
    protected $config = [];

    /** @var  string  Full path to the ignored dotenv file. */
    protected $filePath;

    /** @var  string  Full path to the distributed dotenv template. */
    protected $distFilePath;

    /**
     * Processor constructor.
     *
     * @param  IOInterface  $io
     * @param  array        $config
     */
    public function __construct(IOInterface $io, array $config)
    {
        $this->io = $io;
        $this->config = $config;
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
        $this->filePath = $filePath;
        $this->distFilePath = $distFilePath;
    }

    /**
     * Run the dotenv processor.
     *
     * @return  int
     */
    public function run()
    {
        $this->processConfig($this->config);

        $distLoader = new ReadOnlyLoader($this->distFilePath);
        $distParams = $distLoader->load();
        
        if (is_file($this->filePath)) {
            $loader = new ReadOnlyLoader($this->filePath);
            $params = $loader->load();
        } else {
            $params = [];
        }

        if (count($params) > 0 && !$this->config['keep-outdated']) {
            // If keep-outdated isn't explicitly set true, remove any keys
            // that don't exist in $distFile
            $outdated = array_diff(array_keys($params), array_keys($distParams));
            foreach ($outdated as $key) {
                unset($params[$key]);
            }
        }

        foreach ($distParams as $key => $value) {
            if (!array_key_exists($key, $params)) {
                if (!$this->io->isInteractive()) {
                    // If interactive, prompt for a value for the new key
                    $params[$key] = $this->io->ask(sprintf('<question>%s</question> (<comment>%s</comment>): ', $key, $value), $value);
                } else {
                    // Otherwise just use the dist value
                    $params[$key] = $value;
                }
            }
        }

        // Write to $file.
        return $this->writeOutput($params);
    }

    /**
     * Write the merged parameters to the configured file.
     *
     * @param   array  $params
     * @return  int
     */
    protected function writeOutput(array $params)
    {
        $lines = [];
        foreach ($params as $key => $value) {
            $lines[] = $this->writeLine($key, $value);
        }

        if (!file_put_contents($this->filePath, implode("\n", $lines), LOCK_EX)) {
            $this->io->write(sprintf('<error>The file "%s" could not be written.</error>', $this->config['file']));

            return 1;
        }

        $this->io->write(sprintf('<info>File "%s" written successfully.</info>', $this->config['file']));

        return 0;
    }

    /**
     * Output a line.
     * @param $key
     * @param string $value
     */
    protected function writeLine($key, $value = '')
    {
        // Quote if necessary
        if (preg_match('/\s+/', $value) === 1) {
            $value = "'". preg_replace("/(?<!\\\)'/", "\'", $value) ."'";
        }
        
        return sprintf('%s=%s', $key, $value);
    }
}
