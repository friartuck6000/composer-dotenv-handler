<?php

namespace Ft6k\ComposerDotenv;

use Composer\Script\Event;
use Ft6k\ComposerDotenv\Exception\InvalidArgumentException;

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
        $extra = $event->getComposer()->getPackage()->getExtra();
        if (array_key_exists(self::CONFIG_KEY, $extra)) {
            $config = self::processConfig($extra[self::CONFIG_KEY]);
        } else {
            $config = self::processConfig(null);
        }

        // Initialize and run processor
        $processor = new Processor($event->getIO(), $config);

        return $processor->run();
    }

    /**
     * Process configuration.
     *
     * @param   mixed  $config
     * @return  array
     */
    protected static function processConfig($config)
    {
        $defaults = [
            'file'          => '.env',
            'dist-file'     => '.env.dist',
            'keep-outdated' => false,
        ];

        if ($config && !is_array($config)) {
            throw new InvalidArgumentException(sprintf('The extra.%s parameter must be a configuration object.', self::CONFIG_KEY));
        }

        return array_merge($defaults, $config);
    }
}
