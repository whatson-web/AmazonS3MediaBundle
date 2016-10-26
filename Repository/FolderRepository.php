<?php

namespace WH\AmazonS3MediaBundle\Repository;

use WH\LibBundle\Repository\BaseTreeRepository;

/**
 * Class FolderRepository
 *
 * @package WH\AmazonS3MediaBundle\Repository
 */
class FolderRepository extends BaseTreeRepository
{

	/**
	 * @return string
	 */
	public function getEntityNameQueryBuilder()
	{

		return 'folder';
	}
}
