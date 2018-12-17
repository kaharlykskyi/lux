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
    ]
];