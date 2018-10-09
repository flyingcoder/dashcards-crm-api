# Base URL [api.bizzooka.ca]

## Groups API [api/groups]

## Groups sort API [api/groups?sort={column}|{asc|desc}]

## Groups search API [api/groups?search={keyword}]

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
            'name' : 'Sales Agent'
        }

+ Response 201 (application/json)

        {
            newly created groups object
        }

### /{group-id} [PUT]

+ Request (application/json)

        {
            'name' : 'Sales Agent'
        }

+ Response 200 (application/json)

        {
            updated groups object
        }

### /{group-id} [DELETE]

+ Response 200 (application/json)

        {
            'Group is successfully deleted.'
        }

+ Response 500 (application/json)

        {
            'Failed to delete group.'
        }

### /{group-id}/permission [GET]

+ Response 200 (application/json)

        {
            'Group is successfully deleted.'
        }

+ Response 500 (application/json)

        {
            'Failed to delete group.'
        }
