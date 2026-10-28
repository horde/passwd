<?php

/**
 * Test the backend driver factory.
 *
 * @author     Ralf Lang <ralf.lang@ralf-lang.de>
 * @category   Horde
 * @copyright  2013 Horde LLC
 * @internal
 * @license    http://www.horde.org/licenses/gpl GPL
 * @package    Passwd
 * @subpackage UnitTests
 * @coversNothing
 */

namespace Horde\Passwd\Test\Unit;

class Passwd_Unit_Factory_DriverTest extends TestBase
{
    protected $_backends = [];

    public function setUp(): void
    {
        $this->markTestIncomplete('Factories with configuration files don\'t work out of the box.');
        $this->_backends = [
            'null' => [
                'disabled' => false,
                'name' => 'Null',
                'driver' => 'Null',
                'policy' => [
                    'minLength' => 6,
                    'minNumeric' => 1,
                ],
            ],
        ];
    }

    public function testGettingSubdriversWorks()
    {
        $factory = new Passwd_Factory_Driver($this->getInjector());
        $factory->backends = [];

        $driver = $factory->create('Null', [
            'is_subdriver' => true,
            'driver' => 'Null',
        ]);

        $this->assertInstanceOf('Passwd_Driver', $driver);
    }

    public function testGetBackendsReturnsResultOfSetBackends()
    {
        $GLOBALS['injector'] = $this->getInjector();
        $factory = new Passwd_Factory_Driver($this->getInjector());
        $factory->backends = $this->_backends;

        $this->assertArrayHasKey('null', $factory->backends);
    }

    // This test is currently blocked by a static call
    public function testGettingTheSameDriverTwiceWorks()
    {
        $GLOBALS['injector'] = $this->getInjector();
        $factory = new Passwd_Factory_Driver($this->getInjector());
        $factory->backends = $this->_backends;

        $driver1 = $factory->create('null');
        $driver2 = $factory->create('null');

        $this->assertEquals($driver1, $driver2);
    }

}
