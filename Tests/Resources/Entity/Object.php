<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\ODMAdapter\Mapping\Annotations as ODMAdapter;

/**
 * @ORM\Entity
 * @ORM\Table(name="object")
 * @ODMAdapter\ObjectAdapter
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class Object
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    public $uuid;

    /**
     * @var object
     * @ODMAdapter\ReferencePhpcr(
     *  referencedBy="uuid",
     *  inversedBy="uuid",
     *  targetObject="Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Document\ReferencedDocument",
     *  name="document",
     *  commonField={
     *      @ODMAdapter\CommonField(referencedBy="commonField", inversedBy="commonField")
     *  }
     * )
     */
    public $document;

    /**
     * @ORM\Column(type="string")
     */
    public $commonField;
}
