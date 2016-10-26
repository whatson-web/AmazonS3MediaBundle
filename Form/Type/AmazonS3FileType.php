<?php

namespace WH\AmazonS3MediaBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AmazonS3FileType
 *
 * @package WH\AmazonS3MediaBundle\Form\Type
 */
class AmazonS3FileType extends AbstractType
{

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(
			array(
				'class'    => 'WH\AmazonS3MediaBundle\Entity\File',
				'property' => 'fileName',
			)
		);
	}

	/**
	 * @return mixed
	 */
	public function getParent()
	{
		return EntityType::class;
	}

}