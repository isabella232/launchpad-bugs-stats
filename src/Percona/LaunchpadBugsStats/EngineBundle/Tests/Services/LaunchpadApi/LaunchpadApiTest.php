<?php

namespace Percona\LaunchpadBugsStats\EngineBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Percona\LaunchpadBugsStats\EngineBundle\Services\LaunchpadApi\LaunchpadApi;

class LaunchpadApiTest extends WebTestCase
{
	private $container;

	/**
	 * @var LaunchpadApi
	 */
	private $api;

	public function setUp()
	{
		$client = static::createClient();
		$this->container = $client->getContainer();
		$this->api = $this->container->get('percona.launchpad');
	}

	public function testServiceExists()
	{
		$this->assertInstanceOf(
			'\\Percona\\LaunchpadBugsStats\\EngineBundle\\Services\\LaunchpadApi\\LaunchpadApi',
			$this->api
		);
	}

	public function testMethodGetBugsOfProject()
	{
		$projectName = 'percona-server';
		$bugs = $this->api->getBugsOfProject($projectName);
		$this->assertInternalType('array', $bugs);

		# At this moment percona-server has 290 bugs
		$minimumNumberOfBugs = 290;
		$this->assertGreaterThanOrEqual(
			$minimumNumberOfBugs,
			\count($bugs),
			"{$projectName} should return at least {$minimumNumberOfBugs} bugs, but got " . \count($bugs)
		);

		foreach ($bugs as $bug)
		{
			# $this->debug($bug);
			$this->assertInternalType('object', $bug);
			$this->assertObjectHasAttributes(
				array('id', 'status', 'title'),
				$bug
			);
		}
	}

	public function testMethodGetBugInformation()
	{
		$bugId = 1042517;
		$bug = $this->api->getBugInformation($bugId);

		$this->assertInternalType('object', $bug);
		$this->assertObjectHasAttributes(
			array ('id', 'title', 'description', 'date_created'),
			$bug
		);

		$this->assertEquals($bugId, $bug->id);


		# This bug creation ts is: "2012-08-28T03:22:57.942174+00:00"
		$this->assertEquals("2012-08-28T03:22:57.942174+00:00", $bug->date_created);
	}

	/**
	 * @group full
	 */
	public function testMethodGetFullBugsOfProject()
	{
		$projectName = 'percona-xtradb-cluster';
		$bugs = $this->api->getFullBugsOfProject($projectName);
		$this->assertInternalType('array', $bugs);

		# Number of bugs of {$projectName} at this moment
		$minimumNumberOfBugs = 42;

		$this->assertGreaterThanOrEqual(
			$minimumNumberOfBugs,
			\count($bugs),
			"{$projectName} should return at least {$minimumNumberOfBugs} bugs, but got " . \count($bugs)
		);

		foreach ($bugs as $bug)
		{
			# $this->debug($bug);
			$this->assertInternalType('object', $bug);
			$this->assertObjectHasAttributes(
				array('id', 'status', 'title', 'description', 'date_created'),
				$bug
			);
		}
	}


	# ---- Helpers


	private function assertObjectHasAttributes(array $attributes, $object)
	{
		foreach ($attributes as $attribute)
		{
			$this->assertObjectHasAttribute($attribute, $object);
		}
	}

	private function debug($anything)
	{
		\fwrite(STDOUT, PHP_EOL.var_export($anything,1));
	}
}
