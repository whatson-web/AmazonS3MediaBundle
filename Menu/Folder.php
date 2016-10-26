<?php

namespace WH\AmazonS3MediaBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class Folder
 *
 * @package WH\AmazonS3MediaBundle\Menu
 */
class Folder implements ContainerAwareInterface
{

	use ContainerAwareTrait;

	/**
	 * @param FactoryInterface $factory
	 * @param array            $options
	 *
	 * @return \Knp\Menu\ItemInterface
	 */
	public function tree(FactoryInterface $factory, array $options = array())
	{

		$entityRepository = $this->container->get('doctrine')->getRepository('WHAmazonS3MediaBundle:Folder');

		$folders = $entityRepository->get(
			'all'
		);

		$menu = $factory->createItem(
			'root'
		);

		foreach ($folders as $folder) {

			if ($folder->getLvl() == 0) {

				$menu->addChild(
					$folder->getId(),
					$this->getNodeTree($folder)
				);

				if (count($folder->getChildren()) > 0) {

					$this->treeChildren($menu, $folder->getId(), $folder->getChildren());
				}
			}
		}

		return $menu;
	}

	/**
	 * @param $node
	 * @param $slug
	 * @param $entities
	 *
	 * @return mixed
	 */
	private function treeChildren($node, $slug, $entities)
	{

		foreach ($entities as $entity) {

			$node[$slug]->addChild(
				$entity->getId(),
				$this->getNodeTree($entity)
			);

			if (count($entity->getChildren()) > 0) {

				$this->treeChildren($node[$slug], $entity->getId(), $entity->getChildren());
			}
		}

		return $node;
	}

	/**
	 * @param $folder
	 *
	 * @return array
	 */
	private function getNodeTree($folder)
	{

		return array(
			'label'           => $folder->getName(),
			'route'           => 'bk_wh_amazons3media_file_selectfolder',
			'routeParameters' => array(
				'folderId' => $folder->getId(),
			),
		);
	}

}