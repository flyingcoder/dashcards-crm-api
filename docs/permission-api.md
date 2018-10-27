# Base URL [api.bizzooka.ca]

## Permission API [api/permission]

## Permission sort API [api/permission?sort={column}|{asc|desc}]

## Permission search API [api/permission?search={keyword}]

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
            'name'        : 'My Project Files',
            'slug'        : {
                'create'     : true,
                'view'       : true,
                'update'     : true,
                'delete'     : true,
            },
            'description' : 'Project Files permissions'
        }

+ Response 201 (application/json)

        {
            newly created groups object
        }

### /{permission-id} [PUT]

+ Request (application/json)

        {
            'name'        : 'My Project Files',
            'slug'        : {
                'create'     : true,
                'view'       : true,
                'update'     : true,
                'delete'     : true,
            },
            'description' : 'Project Files permissions'
        }

+ Response 200 (application/json)

        {
            updated groups object
        }

### /{permission-id} [DELETE]

+ Response 200 (application/json)

        {
            'Group is successfully deleted.'
        }

+ Response 500 (application/json)

        {
            'Failed to delete group.'
        }
