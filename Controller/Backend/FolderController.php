<?php

namespace WH\AmazonS3MediaBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WH\BackendBundle\Controller\Backend\BaseController;

/**
 * @Route("/admin/media/folders")
 *
 * Class FolderController
 *
 * @package WH\AmazonS3MediaBundle\Controller\Backend
 */
class FolderController extends BaseController
{

	public $bundlePrefix = 'WH';
	public $bundle = 'AmazonS3MediaBundle';
	public $entity = 'Folder';

	/**
	 * @Route("/create/", name="bk_wh_amazons3media_folder_create")
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function createAction(Request $request)
	{
		$createController = $this->get('bk.wh.back.create_controller');

		return $createController->create($this->getEntityPathConfig(), $request);
	}

	/**
	 * @Route("/update/{id}", name="bk_wh_amazons3media_folder_update")
	 *
	 * @param         $id
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function updateAction($id, Request $request)
	{
		$updateController = $this->get('bk.wh.back.update_controller');

		return $updateController->update($this->getEntityPathConfig(), $id, $request);
	}

	/**
	 * @Route("/delete/{id}", name="bk_wh_amazons3media_folder_delete")
	 *
	 * @param         $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function deleteAction($id)
	{
		$deleteController = $this->get('bk.wh.back.delete_controller');
		// TODO : Listener pour relinker les children ?
		return $deleteController->delete($this->getEntityPathConfig(), $id);
	}

}
