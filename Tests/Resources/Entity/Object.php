<?php

namespace Doctrine\ORM\Bundle\OrmPhpcrAdapterBundle\Tests\Resources\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="object")
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
     */
    public $document;

    /**
     * @ORM\Column(type="string")
     */
    public $commonField;
}
