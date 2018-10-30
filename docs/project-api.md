# Base URL [api.bizzooka.ca]

## Projects API [api/project]

## Projects view API [api/project?view={grid|list}] default view "list"

## Projects sort API [api/project?sort={column}|{asc|desc}]

## Projects search API [api/project?search={keyword}]


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
            'title' => 'Test project',
            'client_id' => client_user_id, //from a client users dropdown
            'service_id' => service_id, //from a services dropdown
            'start_at' => '2018-12-19',
            'end_at' => '2018-12-19',
            'location' => 'required',
            'description' => 'required',
            'comment' => 'test comment',
            'members' => [
                user_id, //from a users dropdown
                user_id //from a users dropdown
            ]

        }

+ Response 201 (application/json)

        {
            newly created project object
        }