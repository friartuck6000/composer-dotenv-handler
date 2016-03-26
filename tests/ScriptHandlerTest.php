<?php

namespace Ft6k\ComposerDotenv\Test;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Script\Event;
use Ft6k\ComposerDotenv\Exception\InvalidArgumentException;
use Ft6k\ComposerDotenv\ScriptHandler;
use Prophecy\Prophecy\ProphecyInterface;


/**
 * @author  Kyle Tucker <kyleatucker@gmail.com>
 */
class ScriptHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ProphecyInterface */
    protected $event;

    /** @var  ProphecyInterface */
    protected $io;

    /** @var  ProphecyInterface */
    protected $package;

    protected function setUp()
    {
        parent::setUp();

        $this->event = $this->prophesize(Event::class);
        $this->io = $this->prophesize(IOInterface::class);
        $this->package = $this->prophesize(PackageInterface::class);

        $composer = $this->prophesize(Composer::class);
        $composer->getPackage()->willReturn($this->package);

        $this->event->getComposer()->willReturn($composer);
        $this->event->getIO()->willReturn($this->io);
    }

    /**
     * @param  array   $extra
     * @param  string  $exceptionMessage
     *
     * @dataProvider       provideInvalidPackageConfigs
     * @expectedException  InvalidArgumentException
     */
    public function testInvalidPackageConfigs(array $extra, $exceptionMessage)
    {
        $this->package->getExtra()->willReturn($extra);
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);

        ScriptHandler::buildParameters($this->event->reveal());
    }

    /**
     * @return  array
     */
    public function provideInvalidPackageConfigs()
    {
        return [
            'invalid type' => [
                [ScriptHandler::CONFIG_KEY => 'string'],
                sprintf('The extra.%s parameter must be a configuration object.', ScriptHandler::CONFIG_KEY),
            ],
        ];
    }
}
