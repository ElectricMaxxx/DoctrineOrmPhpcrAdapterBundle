<?php

namespace Doctrine\ORM\Bundle\OrmPhpcrAdapterBundle\Tests\Resources\Document;

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
}
