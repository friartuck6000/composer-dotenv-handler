<?php

namespace Ft6k\ComposerDotenv;

use Dotenv\Loader;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class ReadOnlyLoader extends Loader
{
    /**
     * ReadOnlyLoader constructor.
     *
     * @param  string  $filePath
     */
    public function __construct($filePath)
    {
        parent::__construct($filePath, true);
    }

    /**
     * Process the file without actually setting any environment variables.
     * In addition, instead of returning all the lines of the file, it parses them and returns
     * an array of the parsed variables as key-value pairs.
     *
     * @return  array
     */
    public function load()
    {
        $this->ensureFileIsReadable();
        $lines = $this->readLinesFromFile($this->filePath);

        $parsed = [];
        foreach ($lines as $line) {
            if (!$this->isComment($line) && $this->looksLikeSetter($line)) {
                list($key, $value) = $this->normaliseEnvironmentVariable($line, null);
                $parsed[$key] = $value;
            }
        }

        return $parsed;
    }
}
