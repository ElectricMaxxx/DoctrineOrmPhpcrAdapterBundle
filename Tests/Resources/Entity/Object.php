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
    public $id;

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

    /**
     * @param mixed $commonField
     */
    public function setCommonField($commonField)
    {
        $this->commonField = $commonField;
    }

    /**
     * @return mixed
     */
    public function getCommonField()
    {
        return $this->commonField;
    }

    /**
     * @param object $document
     */
    public function setDocument($document)
    {
        $this->document = $document;
    }

    /**
     * @return object
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $uuid
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->uuid;
    }
}
