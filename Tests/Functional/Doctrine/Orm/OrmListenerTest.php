<?php

namespace Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Functional\Doctrine\Orm;

use Doctrine\ODM\PHPCR\DocumentManager;
use Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Document\ReferencedDocument;
use Doctrine\ORM\Bundle\DoctrineOrmPhpcrAdapterBundle\Tests\Resources\Entity\Object;
use Doctrine\ORM\EntityManager;
use Symfony\Cmf\Component\Testing\Functional\BaseTestCase;

class OrmListenerTest extends BaseTestCase
{
    protected $base;

    protected function getKernelConfiguration()
    {
        return array(
            'environment' => 'orm',
        );
    }

    public function setUp()
    {
        $this->db('PHPCR')->createTestNode();
        $this->base = $this->getDm()->find(null, '/test');
    }
    /**
     * @return EntityManager
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

    public function testPesistNew()
    {
        $entity = new Object();
        $entity->commonField = 'some value';

        $document = new ReferencedDocument();
        $document->setParentDocument($this->base);
        $document->commonField = 'some value on document';
        $document->setName('test-document');

        $entity->setDocument($document);

        $this->getEm()->persist($entity);
        $this->getEm()->flush();
        $this->getEm()->clear();

        $entity = $this->getEm()->find(get_class($entity), $entity->id);

        $this->assertNotNull($entity);

    }
}
