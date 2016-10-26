<?php

namespace WH\AmazonS3MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="WH\AmazonS3MediaBundle\Repository\FileRepository")
 */
class File
{

	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fileKey", type="string", length=255)
	 */
	private $fileKey;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="fileName", type="string", length=255)
	 */
	private $fileName;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="mimeType", type="string", length=255)
	 */
	private $mimeType;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="publicUrl", type="string", length=255)
	 */
	private $publicUrl;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="alt", type="string", length=255, nullable=true)
	 */
	private $alt;

	/**
	 * @ORM\ManyToOne(targetEntity="Folder", inversedBy="files")
	 */
	private $folder;

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set fileKey
	 *
	 * @param string $fileKey
	 *
	 * @return File
	 */
	public function setFileKey($fileKey)
	{
		$this->fileKey = $fileKey;

		return $this;
	}

	/**
	 * Get fileKey
	 *
	 * @return string
	 */
	public function getFileKey()
	{
		return $this->fileKey;
	}

	/**
	 * Set fileName
	 *
	 * @param string $fileName
	 *
	 * @return File
	 */
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;

		return $this;
	}

	/**
	 * Get fileName
	 *
	 * @return string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}

	/**
	 * Set alt
	 *
	 * @param string $alt
	 *
	 * @return File
	 */
	public function setAlt($alt)
	{
		$this->alt = $alt;

		return $this;
	}

	/**
	 * Get alt
	 *
	 * @return string
	 */
	public function getAlt()
	{
		return $this->alt;
	}

	/**
	 * Set mimeType
	 *
	 * @param string $mimeType
	 *
	 * @return File
	 */
	public function setMimeType($mimeType)
	{
		$this->mimeType = $mimeType;

		return $this;
	}

	/**
	 * Get mimeType
	 *
	 * @return string
	 */
	public function getMimeType()
	{
		return $this->mimeType;
	}

	/**
	 * Set folder
	 *
	 * @param \WH\AmazonS3MediaBundle\Entity\Folder $folder
	 *
	 * @return File
	 */
	public function setFolder(\WH\AmazonS3MediaBundle\Entity\Folder $folder = null)
	{
		$this->folder = $folder;

		return $this;
	}

	/**
	 * Get folder
	 *
	 * @return \WH\AmazonS3MediaBundle\Entity\Folder
	 */
	public function getFolder()
	{
		return $this->folder;
	}

	/**
	 * Set publicUrl
	 *
	 * @param string $publicUrl
	 *
	 * @return File
	 */
	public function setPublicUrl($publicUrl)
	{
		$this->publicUrl = $publicUrl;

		return $this;
	}

	/**
	 * Get publicUrl
	 *
	 * @return string
	 */
	public function getPublicUrl()
	{
		return $this->publicUrl;
	}
}
