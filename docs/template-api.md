# Base URL [api.bizzooka.ca]

## template API [api/template]

## template view API [api/template?view={grid|list}] default view "list"

## template sort API [api/template?sort={column}|{asc|desc}]

## template search API [api/template?search={keyword}]

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
            'name' : 'SEO Milestones',
            'status' : 'Active'

        }

+ Response 201 (application/json)

        {
            newly created template object
        }

### /{template-id} [PUT]

+ Request (application/json)

        {
            'name' : 'Tech Milestone',
            'status' : 'Active'
        }

+ Response 200 (application/json)

        {
            updated template object
        }

### /{template-id} [DELETE]

+ Response 200 (application/json)

        {
            'template is successfully deleted.'
        }

+ Response 500 (application/json)

        {
            'Failed to delete template.'
        }