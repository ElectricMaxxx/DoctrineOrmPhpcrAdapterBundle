# DoctrineOrmPhpcrAdapterBundle

DoctrineOrmPhpcrAdapterBundle for the Symfony Framework.

This bundle integrates the Doctrine ORM-ODM-Adapter into Symfony2.

[![Build Status](https://secure.travis-ci.org/ElectricMaxxx/DoctrineOrmPhpcrAdapterBundle.png)](http://travis-ci.org/ElectricMaxxx/DoctrineOrmPhpcrAdapterBundle)

## What is Doctrine ORM-ODM-Adapter?

The Doctrine Project is the home of a selected set of PHP libraries primarily focused on providing persistence
services and related functionality. Ths ORM-ODM-Adapter provides the chance to reference objects between different
doctrine implementations.


## Documentation

### Minimum Configuration


```yml

doctrine_orm_phpcr_adapter:
    managers:
        reference-phpcr:
            defaul: doctrine_phpcr.odm.default_document_manager
        reference-dbal-orm: 
            default: doctrine.orm.default_entity_manager
    adapters:
        mapping: true
        auto_generate_proxy_classes: %kernel.debug%
        mappings: 
            default:
                type: annotation
                dir: ../Entity
                is_bundle: true
``` 

You can add some mapping for the documents/entities as you used to know it in the ORM/ODM:

Your will find the mapping information in the (library)[https://github.com/ElectricMaxxx/DoctrineOrmOdmAdapter]
