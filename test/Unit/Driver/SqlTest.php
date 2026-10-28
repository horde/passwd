<?php

/**
 * Test the Sql backend driver.
 *
 * @author     Ralf Lang <ralf.lang@ralf-lang.de>
 * @category   Horde
 * @copyright  2013 Horde LLC
 * @internal
 * @package    Passwd
 * @subpackage UnitTests
 * @coversNothing
 */

namespace Horde\Passwd\Test\Unit;

use Horde_Test_Setup;
use Horde_Injector;
use Passwd_Factory_Driver;
use Horde_Db_Adapter_Pdo_Sqlite;
use Passwd_Driver_Sql;

class Passwd_Unit_Driver_SqlTest extends TestBase
{
    private $driver;

    public static function setUpBeforeClass(): void
    {
        self::createBasicPasswdSetup(new Horde_Test_Setup());
        parent::setUpBeforeClass();
    }

    public function setUp(): void
    {
        $GLOBALS['injector'] = $this->getInjector();
        $factory = new Passwd_Factory_Driver($this->getInjector());
        $factory->backends = [];

        // Get a Horde_Db_Adapter to prevent usage of Horde_Core_Factory_Db.
        $db = new Horde_Db_Adapter_Pdo_Sqlite(['dbname' => ':memory:']);
        $db->execute("CREATE TABLE horde_users (
            user_uid VARCHAR(255) PRIMARY KEY NOT NULL,
            user_pass VARCHAR(255) NOT NULL,
            user_soft_expiration_date INTEGER,
            user_hard_expiration_date INTEGER
        );");

        $this->driver = new Passwd_Driver_Sql(['db' => $db]);
    }

    public function testSetup()
    {
        $this->assertInstanceOf('Passwd_Driver', $this->driver);
    }

    /**
     * @expectedException Passwd_Exception
     */
    public function testChangePasswordFailsForNonexistingUser()
    {
        $this->expectExceptionMessage('User not found');
        $res = $this->driver->changePassword('Patricia', 'alt', 'neu');
    }

}
