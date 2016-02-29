<?php

require_once(__DIR__ . '/../../../lib/base.php');

use \OCA\user_shibboleth\DB as DB;

class DBTest extends PHPUnit_Framework_TestCase {

	private static $userX;
	private static $userY;
	private static $userZ;

	private static function cleanUpDatabase() {
		$query = \OC::$server->getDatabaseConnection()->prepare('DELETE FROM *PREFIX*shibboleth_user');
		$query->execute();
	}

	public static function setUpBeforeClass() {
		self::$userX = array(
			'LoginName' => 'MisterX',
			'DisplayName' => 'Mister X',
			'EPPN' => 'mister.x@example.com',
			'HomeDir' => '/dev/null/shibboleth/MisterX');
		self::$userY = array(
			'LoginName' => 'MisterY',
			'DisplayName' => 'Mister Y',
			'EPPN' => 'mister.y@example.com',
			'HomeDir' => '/dev/null/shibboleth/MisterY');
		self::$userZ = array(
			'LoginName' => 'MisterZ',
			'DisplayName' => 'Mister Z',
			'EPPN' => 'mister.z@example.com',
			'HomeDir' => '/dev/null/shibboleth/MisterZ');

		// In case tearDownAfter was not called due to error
		self::cleanUpDatabase();

		DB::addUser(self::$userX['LoginName'], self::$userX['DisplayName'], self::$userX['HomeDir'], self::$userX['EPPN']);
	}

	public static function tearDownAfterClass() {
		self::cleanUpDatabase();
	}

	public function testAddUser() {
		$outcome = DB::addUser(self::$userY['LoginName'], self::$userY['DisplayName'], self::$userY['HomeDir'], self::$userY['EPPN']);
		$this->assertTrue($outcome);

		$outcome = DB::addUser(self::$userZ['LoginName'], self::$userZ['DisplayName'], self::$userZ['HomeDir'], self::$userZ['EPPN']);
		$this->assertTrue($outcome);
	}

	// Run after testAddUser()
	public function testDeleteUser() {
		// Existing user
		$outcome = DB::deleteUser(self::$userZ['LoginName']);
		$this->assertTrue($outcome);
	}

	public function testReAddingUser() {
		$outcome = DB::addUser(self::$userZ['LoginName'], self::$userZ['DisplayName'], self::$userZ['HomeDir'], self::$userZ['EPPN']);
		$this->assertTrue($outcome);
	}

	public function testLoginNameExists() {
		// Existing user
		$this->assertTrue(DB::loginNameExists(self::$userX['LoginName']));

		// Deleted user
		$this->assertTrue(DB::loginNameExists(self::$userZ['LoginName']));

		// Non existing user
		$this->assertFalse(DB::loginNameExists('NonExisting'));
	}

	public function testGetDisplayName() {
		// Existing user
		$displayName = DB::getDisplayName(self::$userX['LoginName']);
		$this->assertEquals($displayName, self::$userX['DisplayName']);

		// Deleted user
		$displayName = DB::getDisplayName(self::$userZ['LoginName']);
		$this->assertEquals($displayName, self::$userZ['DisplayName']);

		// Non existing user
		$displayName = DB::getDisplayName('NonExisting');
		$this->assertFalse($displayName);
	}

	public function testGetHomeDir() {
		// Existing user
		$homeDir = DB::getHomeDir(self::$userX['LoginName']);
		$this->assertEquals($homeDir, self::$userX['HomeDir']);

		// Deleted user
		$homeDir = DB::getHomeDir(self::$userZ['LoginName']);
		$this->assertEquals($homeDir, self::$userZ['HomeDir']);

		// Non existing user
		$homeDir = DB::getHomeDir('NonExisting');
		$this->assertFalse($homeDir);
	}

	public function testUpdateDisplayName() {
		// Existing user
		DB::updateDisplayName(self::$userY['LoginName'], self::$userZ['DisplayName']);
		$displayName = DB::getDisplayName(self::$userY['LoginName']);
		$this->assertEquals($displayName, self::$userZ['DisplayName']);

		// Undo change
		$outcome = DB::updateDisplayName(self::$userY['LoginName'], self::$userY['DisplayName']);
		$this->assertTrue($outcome);
	}

	public function testGetLoginNames() {
		// Test based on login name
		$loginNames = DB::getLoginNames('Mister', 10, 0);
		$success = (in_array(self::$userX['LoginName'], $loginNames) && in_array(self::$userY['LoginName'], $loginNames));
		$this->assertTrue($success);
	}

	public function testGetDisplayNames() {
		$result = DB::getDisplayNames('Mister', 10, 0);
		$this->assertEquals($result[self::$userX['LoginName']], self::$userX['DisplayName']);
		$this->assertEquals($result[self::$userY['LoginName']], self::$userY['DisplayName']);
	}

}
