# Base URL [api.bizzooka.ca]

## Services API [api/services]

## Services view API [api/services?view={grid|list}] default view "list"

## Services sort API [api/services?sort={column}|{asc|desc}]

## Services search API [api/services?search={keyword}]

### / [GET]

+ Response 200 (application/json)

        {
            "total": 50,
            "per_page": 15,
            "current_page": 1,
            "last_page": 4,
            "first_page_url": "http://api.bizzooka.ca?page=1",
            "last_page_url": "http://api.bizzooka.ca?page=4",
            "next_page_url": "http://api.bizzooka.ca?page=2",
            "prev_page_url": null,
            "path": "http://api.bizzooka.ca",
            "from": 1,
            "to": 15,
            "data": [
                {
                    // Result Object
                },
                {
                    // Result Object
                }
            ]
        }

### / [POST]

+ Request (application/json)

        {
            ['name' : 'SEO Services'],
            ['name' : 'Wedding Services']

        }

+ Response 201 (application/json)

        {
            newly created service object
        }

### /{service-id} [PUT]

+ Request (application/json)

        [
            {'name' : 'Tech Services'},
            {'name' : 'SEO Services'}
        ]

+ Response 200 (application/json)

        {
            updated service object
        }

### /{service-id} [DELETE]

+ Response 200 (application/json)

        {
            'Service is successfully deleted.'
        }

+ Response 500 (application/json)

        {
            'Failed to delete Service.'
        }
