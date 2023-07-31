# ReadyMage BetterNewRelic

Magento 2 module for including GraphQL query names and hashes for persisted-query in NewRelic reports.

Add to any Magento 2 project with the following commands:

  ```shell
  composer require readymage/better-newrelic --no-interaction --update-no-dev --prefer-dist
  php bin/magento module:enable ReadyMage_BetterNewRelic
  ```

## Notice

Based on [jomashop/automatic-graphql-transaction-naming-for-new-relic](https://github.com/joma-webdevs/automatic-graphql-transaction-naming-for-new-relic)