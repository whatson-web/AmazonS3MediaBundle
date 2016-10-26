<?php

namespace WH\AmazonS3MediaBundle\Repository;

use WH\LibBundle\Repository\BaseRepository;

/**
 * Class FileRepository
 *
 * @package WH\AmazonS3MediaBundle\Repository
 */
class FileRepository extends BaseRepository
{

	/**
	 * @return string
	 */
	public function getEntityNameQueryBuilder()
	{

		return 'file';
	}

	/**
	 * @return \Doctrine\ORM\QueryBuilder
	 */
	public function getBaseQuery()
	{

		return $this
			->createQueryBuilder('file')
			->addSelect('folder')
			->leftJoin('file.folder', 'folder');
	}
}
