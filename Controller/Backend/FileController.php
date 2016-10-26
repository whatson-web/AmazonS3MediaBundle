<?php

namespace WH\AmazonS3MediaBundle\Controller\Backend;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WH\BackendBundle\Controller\Backend\BaseController;

/**
 * @Route("/admin/media/files")
 *
 * Class FileController
 *
 * @package WH\AmazonS3MediaBundle\Controller\Backend
 */
class FileController extends BaseController
{

	public $bundlePrefix = 'WH';
	public $bundle = 'AmazonS3MediaBundle';
	public $entity = 'File';

	/**
	 * @Route("/selectFolder/{folderId}", name="bk_wh_amazons3media_file_selectfolder")
	 *
	 * @param $folderId
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function selectFolderAction($folderId)
	{
		$this->get('session')->set('medias.folderId', $folderId);

		return $this->redirect(
			$this->generateUrl(
				'bk_wh_amazons3media_file_index'
			)
		);
	}

	/**
	 * @Route("/index/", name="bk_wh_amazons3media_file_index")
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function indexAction(Request $request)
	{
		$em = $this->get('doctrine')->getManager();

		// Si on reçoit un inputId c'est que le finder a été lancé dans le but de remplir la valeur d'un input
		// On le stocke en session pour pouvoir naviguer dans le finder sans devoir garder cet ID dans les URLs
		if ($request->query->get('inputId')) {
			$inputId = $request->query->get('inputId');
			$this->get('session')->set('medias.inputId', $inputId);
		}

		// On reset cet ID si on ouvre le manager dans une fenêtre dédiée uniquement à la manipulation des médias
		if ($request->query->get('windowMode')) {
			$this->get('session')->set('medias.inputId', null);
		}

		// On récupère le folderId de la session pour que l'utilisateur se retrouve dans le dernier dossier qu'il a ouvert
		$folderId = $this->get('session')->get('medias.folderId');

		$renderVars = array();
		$breadcrumb = array();

		$inputId = $this->get('session')->get('medias.inputId');

		$renderVars['inputId'] = $inputId;

		$conditions = array();
		if ($folderId) {
			$conditions['folder.id'] = $folderId;
		} else {
			$conditions['folder.id NULL'] = 1;
		}

		// Chargement des fichiers
		$files = $em->getRepository('WHAmazonS3MediaBundle:File')->get(
			'all',
			array(
				'conditions' => $conditions,
			)
		);
		$renderVars['files'] = $files;

		$breadcrumb['Racine'] = $this->generateUrl(
			'bk_wh_amazons3media_file_index'
		);
		// Chargement du dossier
		if ($folderId) {
			$folderRepository = $em->getRepository('WHAmazonS3MediaBundle:Folder');
			$folder = $folderRepository->get(
				'one',
				array(
					'conditions' => array(
						'folder.id' => $folderId,
					),
				)
			);

			// Breadcrumb
			$path = $folderRepository->getPath($folder);
			foreach ($path as $crumb) {
				$breadcrumb[$crumb->getName()] = $this->generateUrl(
					'bk_wh_amazons3media_file_selectfolder',
					array(
						'folderId' => $crumb->getId(),
					)
				);
			}
		}

		$renderVars['breadcrumb'] = $breadcrumb;
		$renderVars['folderId'] = $folderId;

		return $this->render(
			'@WHAmazonS3Media/Backend/File/index.html.twig',
			$renderVars
		);
	}

	/**
	 * @Route("/delete/{id}", name="bk_wh_amazons3media_file_delete")
	 *
	 * @param         $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function deleteAction($id)
	{
		$deleteController = $this->get('bk.wh.back.delete_controller');

		return $deleteController->delete($this->getEntityPathConfig(), $id);
	}

	/**
	 * @Route("/upload/{folderId}", name="bk_wh_amazons3media_file_upload", requirements={"folderId":".*"})
	 *
	 * @param         $folderId
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function uploadAction($folderId, Request $request)
	{
		$breadcrumb['Racine'] = $this->generateUrl(
			'bk_wh_amazons3media_file_index'
		);
		// Chargement du dossier
		if ($folderId) {
			$em = $this->get('doctrine')->getManager();
			$folderRepository = $em->getRepository('WHAmazonS3MediaBundle:Folder');
			$folder = $folderRepository->get(
				'one',
				array(
					'conditions' => array(
						'folder.id' => $folderId,
					),
				)
			);

			// Breadcrumb
			$path = $folderRepository->getPath($folder);
			foreach ($path as $crumb) {
				$breadcrumb[$crumb->getName()] = $this->generateUrl(
					'bk_wh_amazons3media_file_selectfolder',
					array(
						'folderId' => $crumb->getId(),
					)
				);
			}
		}

		if ($request->getMethod() == 'POST') {
			$amazonS3Manager = $this->get('bk.wh.amazons3media.amazons3manager');
			$file = $request->files->get('file');

			$amazonS3Manager->uploadFile($file, $folderId);
		}

		return $this->render(
			'@WHAmazonS3Media/Backend/File/upload.html.twig',
			array(
				'breadcrumb' => $breadcrumb,
				'folderId'   => $folderId,
			)
		);
	}

	/**
	 * @Route("/view/{id}", name="bk_wh_amazons3media_file_view")
	 *
	 * @param         $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function viewAction($id)
	{
		$em = $this->get('doctrine')->getManager();
		$amazonS3Manager = $this->get('bk.wh.amazons3media.amazons3manager');

		$file = $em->getRepository('WHAmazonS3MediaBundle:File')->get(
			'one',
			array(
				'conditions' => array(
					'file.id' => $id,
				),
			)
		);

		$mimeType = $file->getMimeType();

		$needToDownload = true;
		if (preg_match('#image.*#', $mimeType) || $mimeType == 'application/pdf') {

			$needToDownload = false;
		}

		$response = new Response();
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', $mimeType);
		if ($needToDownload) {
			$response->headers->set('Content-Disposition', 'attachment;filename="' . $file->getFileName() . '"');
			$response->headers->set('Content-Type', 'application/force-download');
		}
		$content = $amazonS3Manager->getFileContent($file->getFileKey());
		$response->setContent($content);
		$response->sendHeaders(false);

		return $response;
	}

	/**
	 * @Route("/download/{id}", name="bk_wh_amazons3media_file_download")
	 *
	 * @param         $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function downloadAction($id)
	{
		$em = $this->get('doctrine')->getManager();
		$amazonS3Manager = $this->get('bk.wh.amazons3media.amazons3manager');

		$file = $em->getRepository('WHAmazonS3MediaBundle:File')->get(
			'one',
			array(
				'conditions' => array(
					'file.id' => $id,
				),
			)
		);

		$mimeType = $file->getMimeType();

		$response = new Response();
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', $mimeType);
		$response->headers->set('Content-Disposition', 'attachment;filename="' . $file->getFileName() . '"');
		$response->headers->set('Content-Type', 'application/force-download');
		$content = $amazonS3Manager->getFileContent($file->getFileKey());
		$response->setContent($content);
		$response->sendHeaders(false);

		return $response;
	}

	/**
	 * @Route("/preview/{id}", name="bk_wh_amazons3media_file_preview", requirements={"folderId":".+"})
	 *
	 * @param $id
	 *
	 * @return Response
	 */
	public function previewAction($id = null)
	{
		$em = $this->get('doctrine')->getManager();

		$file = $em->getRepository('WHAmazonS3MediaBundle:File')->get(
			'one',
			array(
				'conditions' => array(
					'file.id' => $id,
				),
			)
		);

		return $this->render(
			'@WHAmazonS3Media/Backend/File/preview.html.twig',
			array(
				'file' => $file,
			)
		);
	}

}
