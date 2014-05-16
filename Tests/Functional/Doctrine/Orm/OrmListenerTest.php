<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Functional\Doctrine\Orm;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Entity\Object;
use Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Document\ReferencedDocument;
use PHPCR\NodeInterface;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

/**
 * Test should check if the orm listener works correctly and triggeers the right
 * persistence methods on ObjectAdapterManager.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@onit-gmbh.de>
 */
class OrmListenerTest extends BaseTestCase
{
    /**
     * @var NodeInterface
     */
    protected $base;

    protected function getKernelConfiguration()
    {
        return array(
            'environment' => 'orm',
        );
    }

    /**
     * @return ObjectManager
     */
    protected function getEm()
    {
        return $this->db('ORM')->getOm();
    }

    /**
     * @return DocumentManager
     */
    protected function getDm()
    {
        return $this->db('PHPCR')->getOm();
    }

    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->base = $this->getDm()->find(null, '/test');
    }

    public function testOrmPersist()
    {
        $object = new Object();
        $referencedObject = new ReferencedDocument();

        $object->document = $referencedObject;
        $referencedObject->commonField = 'common field on document';
        $referencedObject->name =  'referenced-object';
        $referencedObject->parentDocument = $this->base;

        $this->getEm()->persist($object);

        $this->assertEquals($object->uuid, $referencedObject->uuid);
        $this->assertEquals($object->commonField, $referencedObject->commonField);

        $document = $this->getDm()->find(null, '/test/referenced-object');

        $this->assertNotNull($document);
        $this->assertEquals($referencedObject, $document);


    }
}
