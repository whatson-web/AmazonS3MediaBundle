<?php

namespace WH\AmazonS3MediaBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;
use WH\AmazonS3MediaBundle\Entity\File;

/**
 * Class FileListener
 *
 * @package WH\AmazonS3MediaBundle\Listener
 */
class FileListener implements EventSubscriber
{

	/**
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * @return array
	 */
	public function getSubscribedEvents()
	{
		return array(
			Events::postRemove,
		);
	}

	/**
	 * ApplicationFileItemListener constructor.
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
	}

	/**
	 * @param LifecycleEventArgs $args
	 *
	 * @return bool
	 */
	public function postRemove(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();

		if ($entity instanceof File) {

			$amazonS3Manager = $this->container->get('bk.wh.amazons3media.amazons3manager');
			$amazonS3Manager->deleteFile($entity->getFileKey());
		}

		return true;
	}

}