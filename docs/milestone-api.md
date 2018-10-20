# Base URL [api.bizzooka.ca]

## Milestone API [api/{parent}/{parent_id}/milestone]

## Milestone view API [api/{parent}/{parent_id}/milestone?view={grid|list}] default view "list"

## Milestone sort API [api/{parent}/{parent_id}/milestone?sort={column}|{asc|desc}]

## Milestone search API [api/{parent}/{parent_id}/milestone?search={keyword}]


### / [GET]

+ Response 200 (application/json)

        {
            "total": 50,
            "per_page": 10,
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
            'title' : 'SEO Milestone 1',
            'status' : 'Pending',
            'days' : 4

        }

### /{milestone_id} [PUT]

+ Request (application/json)

        {
            'title' : 'SEO Milestone 1',
            'status' : 'Pending',
            'days' : 4

        }

### /{milestone_id} [DELETE]

+ Response 200 (application/json)

        {
            'Milestone is successfully deleted.'
        }

+ Response 500 (application/json)

        {
            'Failed to delete milestone.'
        }
