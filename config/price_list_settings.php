<?php

/**
 * Настройка импорта прайс-листов от поставщиков
 */

return [
    [
        'email' => 'selivonenko007@gmail.com', //Електронная почта для мониторинга прайс-листов
        'company' => 'ЧПФ "Юникс-Трейд Ко"', //Название поставщика
        'ignore_row_index' => 5, //Количестро строк которые нужно пропустить при импорте
        'data_row' => 7,//Строка с которой начинаються данные для импорта
        'stock_data_one_row' => false, //true если данные про склады и запасы располагаються в одной строке с товаром а не столбцам
        'cells' => [
            'A' => 'articles', //Ячейка с артикулом продукта
            'B' => 'name',//Имя продукта
            'C' => 'brand',//Бренд продукта
            'J' => 'price',//Цена продукта
            'K' => 'currency'//Валюта
        ],
        'stocks' => [ //Склады и остатки на складах
            [
                'column' => 'D',//Колонка в которой находиться информация просклад
                'row' => 6//Строка в которой находиться название склада
            ],
            ['column' => 'E','row' => 6],
            ['column' => 'F','row' => 6],
            ['column' => 'G','row' => 6],
            ['column' => 'H','row' => 6],
            ['column' => 'I','row' => 6]
        ]
    ],
    /*[
        'email' => 'selivonenkovasyl@outlook.com',
        'company' => 'ELIT',
        'ignore_row_index' => 1,
        'data_row' => 3,
        'stock_data_one_row' => false,
        'cells' => [
            'B' => 'articles',
            'D' => 'name',
            'C' => 'brand',
            'F' => 'price',
            'E' => 'short_description'
        ],
        'stocks' => [
            ['column' => 'G','row' => 2],
            ['column' => 'G','row' => 2],
            ['column' => 'I','row' => 2],
            ['column' => 'J','row' => 2],
            ['column' => 'K','row' => 2]
        ]
    ],*/
    [
        'email' => 'selivonenkovasyl@outlook.com',
        'company' => '00949',
        'ignore_row_index' => 4,
        'data_row' => 5,
        'stock_data_one_row' => true,
        'cells' => [
            'C' => 'articles',
            'D' => 'name',
            'B' => 'brand',
            'F' => 'price',
        ],
        'stocks' => [
            'count' => 'E', //остатки на складе
            'name' => null//имя склада
        ]
    ],
/*    [
        'email' => 'selivonenkovasyl@outlook.com',
        'company' => 'OMEGA',
        'ignore_row_index' => 2,
        'data_row' => 3,
        'stock_data_one_row' => true,
        'cells' => [
            'C' => 'articles',
            'D' => 'name',
            'A' => 'brand',
            'F' => 'price',
        ],
        'stocks' => ['count' => 'E','name' => 'G']
    ]*/
];