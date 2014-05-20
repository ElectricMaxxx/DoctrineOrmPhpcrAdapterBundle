<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;

/**
 * @PHPCRODM\Document
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class ReferencedDocument
{
    /**
     * @PHPCRODM\Uuid
     */
    public $uuid;

    /**
     * @var object
     */
    public $object;

    /**
     * @PHPCRODM\String
     */
    public $commonField;

    /**
     * @PHPCRODM\Id
     */
    public $id;

    /**
     * @PHPCRODM\Node
     */
    public $node;

    /**
     *  @PHPCRODM\ParentDocument
     */
    public $parentDocument;

    /**
     * @PHPCRODM\Nodename
     */
    public $name;

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
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $node
     */
    public function setNode($node)
    {
        $this->node = $node;
    }

    /**
     * @return mixed
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param object $object
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $parentDocument
     */
    public function setParentDocument($parentDocument)
    {
        $this->parentDocument = $parentDocument;
    }

    /**
     * @return mixed
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
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
