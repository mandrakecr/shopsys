<?php

namespace SS6\ShopBundle\Component\DataFixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use SS6\ShopBundle\Component\DataFixture\PersistentReferenceFacade;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class AbstractReferenceFixture extends AbstractFixture implements ContainerAwareInterface {

	/**
	 * @var \Symfony\Component\DependencyInjection\ContainerInterface
	 */
	protected $container;

	/**
	 * @var \SS6\ShopBundle\Component\DataFixture\PersistentReferenceFacade
	 */
	private $persistentReferenceFacade;

	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
	 */
	public function setContainer(ContainerInterface $container = null) {
		$this->container = $container;
		$this->persistentReferenceFacade = $this->get(PersistentReferenceFacade::class);
	}

	/**
	 * @param string $serviceId
	 * @return mixed
	 */
	protected function get($serviceId) {
		return $this->container->get($serviceId);
	}

	/**
	 * @param string $name
	 * @param object $object
	 * @param bool $persistent
	 */
	public function addReference($name, $object, $persistent = true) {
		parent::addReference($name, $object);

		if ($persistent) {
			$this->persistentReferenceFacade->persistReference($name, $object);
		}
	}

	/**
	 * @param string $name
	 * @param object $object
	 * @param bool $persistent
	 */
	public function setReference($name, $object, $persistent = true) {
		parent::setReference($name, $object);

		if ($persistent) {
			$this->persistentReferenceFacade->persistReference($name, $object);
		}
	}

}
