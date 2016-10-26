<?php

namespace WH\AmazonS3MediaBundle\Services;

use GuzzleHttp\Psr7\InflateStream;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use WH\AmazonS3MediaBundle\Entity\File;

/**
 * Class AmazonS3Manager
 *
 * @package WH\AmazonS3MediaBundle\Services
 */
class AmazonS3Manager
{

	protected $container;
	private $s3Client;

	/**
	 * SearchController constructor.
	 *
	 * @param ContainerInterface $container
	 */
	public function __construct(ContainerInterface $container)
	{
		$this->container = $container;
		$this->s3Client = $this->container->get('aws.s3');
	}

	/**
	 * @param UploadedFile $uploadedFile
	 * @param null         $folderId
	 *
	 * @return bool
	 */
	public function uploadFile(UploadedFile $uploadedFile, $folderId = null)
	{
		$fileContent = file_get_contents($uploadedFile->getPath() . '/' . $uploadedFile->getFilename());

		$fileName = $uploadedFile->getClientOriginalName();
		$fileKey = uniqid() . '-' . $fileName;

		$this->s3Client->upload(
			$this->container->getParameter('aws.s3.bucket'),
			$fileKey,
			$fileContent,
			'public-read'
		);

		$file = new File();
		$file->setFileKey($fileKey);
		$file->setFileName($fileName);
		$file->setMimeType($uploadedFile->getMimeType());
		$file->setPublicUrl($this->getFilePublicUrl($fileKey));

		if ($folderId) {
			$em = $this->container->get('doctrine');
			$folder = $em->getRepository('WHAmazonS3MediaBundle:Folder')->get(
				'one',
				array(
					'conditions' => array(
						'folder.id' => $folderId,
					),
				)
			);
			$file->setFolder($folder);
		}

		$em = $this->container->get('doctrine')->getManager();
		$em->persist($file);
		$em->flush();

		return true;
	}

	/**
	 * @param $fileKey
	 *
	 * @return \Aws\Result
	 */
	public function getFile($fileKey)
	{
		$object = $this->s3Client->getObject(
			array(
				'Bucket' => $this->container->getParameter('aws.s3.bucket'),
				'Key'    => $fileKey,
			)
		);

		return $object;
	}

	/**
	 * @param $fileKey
	 *
	 * @return string
	 */
	public function getFilePublicUrl($fileKey)
	{
		$region = $this->container->getParameter('aws.s3.region');
		$bucket = $this->container->getParameter('aws.s3.bucket');
		$filePublicUrl = 'https://s3.' . $region . '.amazonaws.com/' . $bucket . '/' . $fileKey;

		return $filePublicUrl;
	}

	/**
	 * @param $fileKey
	 *
	 * @return \Aws\Result
	 */
	public function getFileContent($fileKey)
	{
		$filePublicUrl = $this->getFilePublicUrl($fileKey);
		$content = file_get_contents($filePublicUrl);

		return $content;
	}

	/**
	 * @param $fileKey
	 *
	 * @return bool
	 */
	public function deleteFile($fileKey)
	{
		$this->s3Client->deleteObject(
			array(
				'Bucket' => $this->container->getParameter('aws.s3.bucket'),
				'Key'    => $fileKey,
			)
		);

		return true;
	}

}
