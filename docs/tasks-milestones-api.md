# Base URL [api.bizzooka.ca]

## Task API [api/milestone/{milestone_id}/task]

## Task view API [api/milestone/{milestone_id}/task?view={grid|list}] default view "list"

## Task sort API [api/milestone/{milestone_id}/task?sort={column}|{asc|desc}]

## Task search API [api/milestone/{milestone_id}/task?search={keyword}]

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
            'title' : 'This is a title created in task test.',
            'description' : 'Mocking the description of task test',
            'status' : 'open',
            'days' : 7
        }

+ Response 201 (application/json)

        {
            newly created task object
        }

### /{task-id} [PUT]

+ Request (application/json)

        {
            'title' : 'This is a title created in task test.',
            'description' : 'Mocking the description of task test',
            'status' : 'open',
            'days' : 7
        }

+ Response 200 (application/json)

        {
            updated task object
        }

### /{task-id} [DELETE]

+ Response 200 (application/json)

        {
            'Task is successfully deleted.'
        }

+ Response 500 (application/json)

        {
            'Failed to delete task.'
        }