<?php

namespace WH\AmazonS3MediaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use WH\LibBundle\Entity\Tree;

/**
 * Class Folder
 *
 * @ORM\Table(name="folder")
 * @ORM\Entity(repositoryClass="WH\AmazonS3MediaBundle\Repository\FolderRepository")
 *
 * @Gedmo\Tree(type="nested")
 *
 * @package WH\AmazonS3MediaBundle\Entity
 */
class Folder
{

	use Tree;

	/**
	 * Folder constructor.
	 */
	public function __construct()
	{

		$this->children = new ArrayCollection();
	}

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
	 * @ORM\Column(name="name", type="string", length=255)
	 */
	private $name;

	/**
	 * @Gedmo\TreeRoot
	 * @ORM\ManyToOne(targetEntity="Folder")
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
	 */
	private $root;

	/**
	 * @Gedmo\TreeParent
	 * @ORM\ManyToOne(targetEntity="Folder", inversedBy="children")
	 * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
	 */
	private $parent;

	/**
	 * @ORM\OneToMany(targetEntity="Folder", mappedBy="parent")
	 * @ORM\OrderBy({"lft" = "ASC"})
	 */
	private $children;

	/**
	 * @ORM\OneToMany(targetEntity="File", mappedBy="folder")
	 */
	private $files;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return Folder
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

    /**
     * Get name indented
     *
     * @return string
     */
    public function getIndentedName()
    {

        return str_repeat(" > ", $this->lvl).$this->name;
    }

	/**
	 * Set root
	 *
	 * @param \WH\AmazonS3MediaBundle\Entity\Folder $root
	 *
	 * @return Folder
	 */
	public function setRoot(\WH\AmazonS3MediaBundle\Entity\Folder $root = null)
	{
		$this->root = $root;

		return $this;
	}

	/**
	 * Get root
	 *
	 * @return \WH\AmazonS3MediaBundle\Entity\Folder
	 */
	public function getRoot()
	{
		return $this->root;
	}

	/**
	 * Set parent
	 *
	 * @param \WH\AmazonS3MediaBundle\Entity\Folder $parent
	 *
	 * @return Folder
	 */
	public function setParent(\WH\AmazonS3MediaBundle\Entity\Folder $parent = null)
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * Get parent
	 *
	 * @return \WH\AmazonS3MediaBundle\Entity\Folder
	 */
	public function getParent()
	{
		return $this->parent;
	}

	/**
	 * Add file
	 *
	 * @param \WH\AmazonS3MediaBundle\Entity\File $file
	 *
	 * @return Folder
	 */
	public function addFile(\WH\AmazonS3MediaBundle\Entity\File $file)
	{
		$this->files[] = $file;

		return $this;
	}

	/**
	 * Remove file
	 *
	 * @param \WH\AmazonS3MediaBundle\Entity\File $file
	 */
	public function removeFile(\WH\AmazonS3MediaBundle\Entity\File $file)
	{
		$this->files->removeElement($file);
	}

	/**
	 * Get files
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getFiles()
	{
		return $this->files;
	}

	/**
	 * Add child
	 *
	 * @param \WH\AmazonS3MediaBundle\Entity\Folder $child
	 *
	 * @return Folder
	 */
	public function addChild(\WH\AmazonS3MediaBundle\Entity\Folder $child)
	{
		$this->children[] = $child;

		return $this;
	}

	/**
	 * Remove child
	 *
	 * @param \WH\AmazonS3MediaBundle\Entity\Folder $child
	 */
	public function removeChild(\WH\AmazonS3MediaBundle\Entity\Folder $child)
	{
		$this->children->removeElement($child);
	}

	/**
	 * Get children
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getChildren()
	{
		return $this->children;
	}
}
