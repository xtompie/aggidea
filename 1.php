<?php

$express = [
    '_table' => 'article',
    'id' => '1234',
    'name' => $model->name(),
    'author' => [
        '_table' => 'user',
        'id' => '1234',
        'name' => 'Tomek',
    ],
    'reviewer_id' => $mode->revieverId(),
    '_rel' => [
        'attributes' => [
            [
                '_table' => 'article_attribute',
                '_id' => [
                    'article_id' => $model->id(),
                    'name' => $model->attributes()[0]['name'],
                ],
                'article_id' => $model->id(),
                'name' => $model->attributes()[0]['name'],
                'value' => $model->attributes()[0]['value'],
            ]
        ],
        'paragraphs' => [
            [
                '_table' => 'article_paragraph',
                'id' => $model->paragraphs()[0]->id(),
            ]
        ],
        'imgs' => [
            [
                '_table' => 'article_img',
                '_primary' => [
                    'article_id' => $model->id(),
                    'img_id' => $model->img[0],
                ],
            ]
        ],
        'comment' => [
            [
                '_table' => 'commentable',
                '_id' => [
                    'commentable_type' => 'article',
                    'commentable_id' => $model->id(),
                ],
                'text' => $model->commnets()[0]->text(),
            ],
        ],
        'article_data' => [
            [
                '_table' => 'article_data',
                'id' => $model->id(),
                'data' => $mode->data(),
            ]
        ],
        'x' => map($model->x(), fn ($x) => [
            '_table' => 'x',
            '_id' => [
                'x_type' => 'article',
                'x_id' => $x->id(),
            ],
            'x_data' => $model->commnets()[0]->text(),
            '_on_update' => []
        ])


    ]
];

// - shop2 caly
// - tree
// - co z autoincrementid jak insert jest
// - wiele relacji moze z $index
// - created at, updateed at - domena chce, bez domeny