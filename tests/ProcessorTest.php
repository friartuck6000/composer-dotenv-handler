<?php

namespace Ft6k\ComposerDotenv\Test;

use Composer\IO\IOInterface;
use Ft6k\ComposerDotenv\Exception\InvalidArgumentException;
use Ft6k\ComposerDotenv\Processor;
use Symfony\Component\Yaml\Yaml;

/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class ProcessorTest extends \PHPUnit_Framework_TestCase
{
    /** @var  object */
    protected $io;

    protected function setUp()
    {
        parent::setUp();

        $this->io = $this->prophesize(IOInterface::class);
    }

    public function testNonexistentDistFile()
    {
        $config = [
            'file'          => 'invalid/path/to/.env',
            'dist-file'     => 'invalid/path/to/.env.dist',
            'keep-outdated' => false,
        ];

        $distPath = getcwd() .'/'. $config['dist-file'];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('The specified dist-file "%s" does not exist.', $distPath));

        new Processor($this->io->reveal(), $config);
    }

    /**
     * @param  string  $testName
     *
     * @dataProvider  provideTestCases
     */
    public function testRun($testName)
    {
        $dataDir = __DIR__ .'/fixtures/processor/'. $testName;
        $tmpDir = sys_get_temp_dir() .'/composer-dotenv';

        $testData = array_replace_recursive([
            'config' => [],
            'interactive' => false,
        ], Yaml::parse(file_get_contents($dataDir .'/init.yml')));
    }

    /**
     * Provide test case data.
     *
     * @return  array
     */
    public function provideTestCases()
    {
        $tests = [];

        foreach (glob(__DIR__ .'/fixtures/processor/*/') as $dir) {
            $tests[] = [basename($dir)];
        }

        return $tests;
    }
}
