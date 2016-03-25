<?php

namespace Ft6k\ComposerDotenv\Test;

use Ft6k\ComposerDotenv\ReadOnlyLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class ReadOnlyLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the load() method, comparing the parsed result to an expected result.
     *
     * @param  string  $dir
     *
     * @dataProvider provideLoadPaths
     */
    public function testLoad($dir)
    {
        // Build loader and process input file
        $loader = new ReadOnlyLoader($dir .'/input.env');
        $loaded = $loader->load();

        // Parse expected file
        $expected = Yaml::parse(file_get_contents($dir .'/expected.yml'));

        // Make assertions
        $this->assertSame($expected, $loaded);

        unset($loader);
    }

    /**
     * Grab fixture paths to pass to testLoad().
     *
     * @return  array
     */
    public function provideLoadPaths()
    {
        $tests = [];

        foreach (glob(__DIR__ .'/fixtures/read_only_loader/*/') as $dir) {
            $tests[basename($dir)] = [$dir];
        }

        return $tests;
    }
}
