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

        print("persist\n");
        $this->getEm()->persist($object);
        $this->getEm()->flush();
        $this->getEm()->clear();

        $this->assertEquals($object->uuid, $referencedObject->uuid);
        $this->assertEquals($object->commonField, $referencedObject->commonField);

        $document = $this->getDm()->find(null, '/test/referenced-object');

        $this->assertNotNull($document);
        $this->assertEquals($referencedObject, $document);
    }

    public function testOrmUpdate()
    {
        $object = new Object();
        $referencedObject = new ReferencedDocument();

        $object->document = $referencedObject;
        $referencedObject->commonField = 'common field on document';
        $referencedObject->name =  'referenced-object';
        $referencedObject->parentDocument = $this->base;

        print("first persist: \n");
        $this->getEm()->persist($object);
        print("first flush: \n");
        $this->getEm()->flush();
        print("first clear: \n");
        $this->getEm()->clear();


        $this->assertEquals('common field on document', $object->getCommonField());
        $this->assertEquals('common field on document', $referencedObject->getCommonField());

        // get the object again from repository
        $object = $this->getEm()->find(get_class($object), $object->id);

        $this->assertNotNull($object);

        $referencedObject = $object->getDocument();
        $this->assertNotNull($referencedObject);
        $referencedObject->setCommonField('an other value');

        print("second persist: \n");
        $this->getEm()->persist($object);
        print("second flush: \n");
        $this->getEm()->flush();
        print("second clear: \n");
        $this->getEm()->clear();


        $referencedObject = $this->getDm()->find(null, '/test/referenced-object');
        $object = $this->getEm()->find(get_class($object), $object->id);

        $this->assertEquals('new value', $object->getCommonField());
        $this->assertEquals('an other value', $referencedObject->getCommonField());
    }
}
