# [Upgrade from v7.2.0 to Unreleased](https://github.com/shopsys/shopsys/compare/v7.2.0...HEAD)

This guide contains instructions to upgrade from version v7.2.0 to Unreleased.

**Before you start, don't forget to take a look at [general instructions](/UPGRADE.md) about upgrading.**
There you can find links to upgrade notes for other versions too.

## [shopsys/framework]

### Infrastructure
- update elasticsearch build configuration ([#1069](https://github.com/shopsys/shopsys/pull/1069))
    - copy new Dockerfile from [shopsys/project-base](https://github.com/shopsys/project-base/blob/master/docker/elasticsearch/Dockerfile) repository
    - update `docker-compose.yml` and `docker-compose.yml.dist`
        ```diff
            elasticsearch:
        -       image: docker.elastic.co/elasticsearch/elasticsearch-oss:6.3.2
        +       build:
        +           context: .
        +           dockerfile: docker/elasticsearch/Dockerfile
                container_name: shopsys-framework-elasticsearch
                ulimits:
                    nofile:
                        soft: 65536
                        hard: 65536
                ports:
                    - "9200:9200"
                volumes:
                    - elasticsearch-data:/usr/share/elasticsearch/data
                environment:
                    - discovery.type=single-node
        ```

### Application
- call `Form::isSubmitted()` before `Form::isValid()` ([#1041](https://github.com/shopsys/shopsys/pull/1041))
    - search for `$form->isValid() && $form->isSubmitted()` and fix the order of calls (in `shopsys/project-base` the wrong order could have been found in `src/Shopsys/ShopBundle/Controller/Front/PersonalDataController.php`):
        ```diff
        - if ($form->isValid() && $form->isSubmitted()) {
        + if ($form->isSubmitted() && $form->isValid()) {
        ```
- fix the typo in Twig template `@ShopsysShop/Front/Content/Category/panel.html.twig` ([#1043](https://github.com/shopsys/shopsys/pull/1043))
    - `categoriyWithLazyLoadedVisibleChildren` ⟶ `categoryWithLazyLoadedVisibleChildren`
- follow instructions in [the separate article](upgrade-instructions-for-read-model-for-product-lists.md) to introduce read model for frontend product lists into your project ([#1018](https://github.com/shopsys/shopsys/pull/1018))
    - we recommend to read [Introduction to Read Model](/docs/model/introduction-to-read-model.md) article
- change `name.keyword` field in ElasticSearch to sort correctly by language ([#1069](https://github.com/shopsys/shopsys/pull/1069))
    - update field `name.keyword` to type `icu_collation_keyword` in `src/Shopsys/ShopBundle/Resources/definition/product/*.json`:
        - `src/Shopsys/ShopBundle/Resources/definition/product/1.json`
            ```diff
                "name": {
                    "type": "text",
                    "analyzer": "stemming",
                    "fields": {
                        "keyword": {
            -               "type": "keyword"
            +               "type": "icu_collation_keyword",
            +               "language": "en",
            +               "index": false
                        }
                    }
                }
            ```
        - `src/Shopsys/ShopBundle/Resources/definition/product/2.json`
            ```diff
                "name": {
                    "type": "text",
                    "analyzer": "stemming",
                    "fields": {
                        "keyword": {
            -               "type": "keyword"
            +               "type": "icu_collation_keyword",
            +               "language": "cs",
            +               "index": false
                        }
                    }
                }
            ```

### Tools
- we recommend upgrading PHPStan to level 4 [#1040](https://github.com/shopsys/shopsys/pull/1040)
    - you'll find detailed instructions in separate article [Upgrade Instructions for Upgrading PHPStan to Level 4](/docs/upgrade/phpstan-level-4.md)

[shopsys/framework]: https://github.com/shopsys/framework
