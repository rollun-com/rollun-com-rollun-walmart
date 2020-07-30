## Walmart API connector ##
Библиотека предоставляет методы для получения и изменения информации в walmart средствами [API](https://developer.walmart.com/#/apicenter/marketPlace/latest#introduction).

В первую очередь вам нужно указать **WALMART_CLIENT_ID** и **WALMART_CLIENT_SECRET** в .env файле. Если вы хотите использовать SANDBOX тогда укажите **WALMART_SANDBOX=1**

Для получения товаров:
```php
<?php

use rollun\Walmart\Sdk\Item;

$item = new Item();

// get all items
$allItems = $item->getItems(); // array

// Response example:
//(
//    [ItemResponse] => Array
//        (
//            [0] => Array
//                (
//                    [mart] => WALMART_US
//                    [sku] => 1235520056
//                    [wpid] => 0RGPJO2Y3PHV
//                    [upc] => 840655077367
//                    [gtin] => 00840655077367
//                    [productName] => EBC Double H Sintered Brake Pads FA407HH
//                    [shelf] => ["UNNAV"]
//                    [productType] => Brake Shoes, Pads & Drums
//                    [price] => Array
//                        (
//                            [currency] => USD
//                            [amount] => 41
//                        )
//                    [publishedStatus] => PUBLISHED
//                )
//        )
//    [totalItems] => 569
//    [nextCursor] => AoE/GjBST0ZLWFpDMU1IUTBTRUxMRVJfT0ZGRVI2NkI5NTNERkNEQUE0M0REODY5N0E0MUEzQUNFM0VBRg==
//)

// get item by sku
$itemsWithSomeSku = $item->getItems('112233'); // array

// limiting
$limit = 2;
$offset = 0;

$items = $item->getItems('', $limit, $offset); // array
```

Для получения количества:
```php
<?php

use rollun\Walmart\Sdk\Inventory;

$inventory = new Inventory();

$sku = '1235520056';

$data = $inventory->getInventory($sku);

// Response example:
//(
//    [sku] => 1235520056
//    [quantity] => Array
//        (
//            [unit] => EACH
//            [amount] => 3
//        )
//)
```

Для изменения количества:
```php
<?php

use rollun\Walmart\Sdk\Inventory;

$inventory = [
    'sku'      => '1235520056',
    'quantity' => [
        'unit'   => 'EACH',
        'amount' => 3
    ]
];

$inventory = (new Inventory())->updateInventory($inventory);
```

Для изменения цены:
```php
<?php

use rollun\Walmart\Sdk\Price;

$sku = '1235520056';
$amount = 55.21;
$currency = 'USD';

$response = (new Price())->updateRegularPrice($sku, $amount, $currency);
```