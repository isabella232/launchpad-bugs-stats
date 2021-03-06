<?php

namespace Percona\LaunchpadBugsStats\ModelBundle\Service\Model;

# Services
use Symfony\Bridge\Monolog\Logger;


# ----


class Model
{
	/**
	 * @var $em EntityManager
	 */
	private $em;

	public function __construct($em)
	{
		$this->em = $em;
	}

	# ---- Repositories


	private function getRepository($entityName)
	{
		return $this->em->getRepository('PerconaLaunchpadBugsStatsModelBundle:' . $entityName);
	}


	# ---- Logs

	/**
	 * @var $logger Logger
	 */
	private $Logger;

	public function setLogger(Logger $Logger)
	{
		$this->Logger = $Logger;
	}

	private function debug($txt)
	{
		$this->Logger->debug($txt);
	}

	private function error($txt)
	{
		$this->Logger->err($txt);
	}

	public function disableLogging()
	{
		$this->Logger = NULL;
	}

}