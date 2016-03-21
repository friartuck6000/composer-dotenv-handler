<?php

namespace Ft6k\ComposerDotenv;

use Composer\Script\Event;

/**
 * Script handler for processing dotenv parameters.
 *
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class ScriptHandler
{
    /** The configuration key used in composer.json's `extra` field. */
    const CONFIG_KEY = 'dotenv';

    /**
     * Run the parameter handler.
     *
     * @param   Event  $event
     * @return  int
     */
    public static function buildParameters(Event $event)
    {
        // Set default parameters
        $config = [
            'file'      => '.env',
            'dist-file' => '.env.dist',
        ];

        // Get composer extra config and merge if applicable
        $extra = $event->getComposer()->getPackage()->getExtra();
        if (isset($extra[self::CONFIG_KEY])) {
            // Filter out any empty parameters before merging with defaults
            $readConfig = array_filter($extra[self::CONFIG_KEY]);
            $config = array_merge($config, $readConfig);
        }

        // Initialize and run processor
        $processor = new Processor($event->getIO(), $config);

        return $processor->run();
    }
}
