# Aggidea

> :warning: **DRAFT** this code dosent work currently, its only a draft

## Projection

Projection is a special data struct containing only PHP primitives.
It lives in infrastrucutre layer.
It has 2 purposes.
The first one is the state of aggregate.
The second one is information how to map the state to relational database to store it. Eg.
```php
[
    ':table' => 'orders', // table name
    'id' => '692e31bd-b87f-4d44-af60-c2a58fcdde18',
    'user_id' => '4dadc7ca-6d40-5592-a642-07725d3ee895', // other aggregate id
    'sum' => '233,93',
    'status' => 'new',
    'billing' => '{"street": "North", /* ... */}', // serialized value object
    'products' => [
        [
            ':table' => 'order_products'
            'id' => '7fcc4773-ebf2-40c1-8556-55cfa2b63cd1',
            'order_id' => '692e31bd-b87f-4d44-af60-c2a58fcdde18',
            'name' => 'Product1',
            'price' => '230,00',
        ],
        // ...
    ],
];
```

- `:table` - informs which table will be used to store it
- `id` - identifier
-  values that are scalars are database fields
-  values that are arrays are local entities

## Presisting

`OrderRepository->save(Order $order);` OrderRepository can map domain Order to projection.
Next `ProjectionPresister->presist($futureProjeciton, callable $presentProjectionProvider)`
in one database transaction calls `$presentProjectionProvider()` to get `$presetProjection`.
Calculates inserts, updated, deletes between $futureProjeciton and $presentProjection, and execute them.

## Fetching aggregates

`OrderRepository->findById(string $id);`
First step is to load projection from database.
ProjectionFetcher helps with that. It use special PQL (Projection Query Language).
It is a simple array:

```php
// pql
[
    'from' => 'orders', // table name
    'where' => [
        'id' => $id,
    ]
    'pql:children:products' => [
        'from' => 'order_products'
        'pql:parent' => 'order_id',
    ],
];
```

ProjectionFetcher gives list of projeciton. We need first. Next the domain object Order is created using ths projection.

## Generating Aggregate IDs

Domain generates aggregates IDs. Domain object are always valid. Entity without id is not valid.

## Local entity id in aggregate

```php
// projection
[
    ':table' => 'orders',
    'id' => '692e31bd-b87f-4d44-af60-c2a58fcdde18'
    // ...
    'products' => [
        [
            ':table' => 'order_products'
            'id' => '692e31bd-b87f-4d44-af60-c2a58fcdde18:0',
            // parent id + offset - only for presisting, dont exists in domain

            'order_id' => '692e31bd-b87f-4d44-af60-c2a58fcdde18',
            // ...
        ],
        // ...
    ],
];
```

## Local entities order

```php
// projection
[
    ':table' => 'orders',
    'id' => '692e31bd-b87f-4d44-af60-c2a58fcdde18'
    // ...
    'products' => [
        [
            ':table' => 'order_products'
            'id' => '692e31bd-b87f-4d44-af60-c2a58fcdde18:0',
            'order_id' => '692e31bd-b87f-4d44-af60-c2a58fcdde18',
            'index' => 0, // index generated when iterating throught order products
            // ...
        ],
        // ...
    ],
];
// pql
[
    'from' => 'orders', // table name
    'where' => [
        'id' => $id,
    ]
    'pql:children:products' => [
        'from' => 'order_products'
        'pql:parent' => 'order_id',
        'order' => 'index ASC',
    ],
];
```

## Lazy loading

Currently no lazy loading. Make aggregate small as possible.

## Model and data migration

Done in infrastructure layer. Eg in `OrderRepository->aggregate()`.

## 2 entities same table

In Doctrine for a given entity manager, it's strictly one entity per table.
Here easily can be one User in Ordering bounded context, and second User in Auth bounded context that maps to the same users table.

## Multitenancy

It can by added in infrastructure layer. The domain may not even know about it.

```php
// projection
[
    ':table' => 'orders',
    'id' => '692e31bd-b87f-4d44-af60-c2a58fcdde18'
    'tenat_id' => $this->tenant->id(), // `$this->tenant` Injected (IoC)
    // ...
];
// pql
[
    'from' => 'orders', // table name
    'where' => [
        'id' => $id,
        'tenant_id' => $this->tenant->id(),
    ]
    'pql:children:products' => [
        'from' => 'order_products'
        'pql:parent' => 'order_id',
        'order' => 'index ASC',
    ],
];
```

## Revisions

With projections revisions can now easily be done. Eg. OrderRevision and OrderRevisionRepository
OrderSerializer uses OrderRepository->aggregate() where data migration is done.

## TODO

- optimistic locking mechanism

## Thoughts

### Merge Projection Presister nad Fetcher

Usually repository preists and loads agregates.
Maybe `ProjectionDAO`. Trying to not using `Manger` word.
Merge or just create Facade.
`DAO` stands for data access, and `Manager` nobody knows what exacly it is doing.

### Clear/onion/hexagonal architecture

In this idea the domain dont know nothing about infrastrucure.
No annotations/attributes in domain.
For each aggregate mapping must by done.
But its simple task.

When we need set/get private/protected property reflection mechnism must be used.
Or we can break the clear architecure and add public setter/getter.

### Abstract Repostiory

Currently dont see any need to create some abstract repository class.
In this solution repository is simple and elastic.
Some times function `aggregate` can be public like in OrderRepository, where is used by OrderSerializer.

Sometimes we can have Domain object `Node` that will have method `childNodes(): NodeCollection`.
The method `aggregate` can have some protected recursive method to build the aggregate.
Then the migration can work in recursive method only one node not on all projection.
The `migration` method can by optional added only when there is a need for this.

### AQL, PQL, ProjectionPresisterDAO, DAO, PDO

Currently, the Presiter layer is shredded into small pieces. Maybe someone wants to use some ORM for this.

