# Base URL [api.bizzooka.ca]

## Teams API [api/company/teams]

## Teams view API [api/company/teams?view={grid|list}] default view "list"

## Teams sort API [api/company/teams?sort={column}|{asc|desc}]

## Teams search API [api/company/teams?search={keyword}]

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
            'first_name' : 'Alvin',
            'last_name' : 'Pacot',
            'email' : 'sample@email.com',
            'password' : 'securepassword',
            'telephone' : '+1323453234',
            'password_confirmation' : 'securepassword',
            'group_name' : 'Managers',
            'job_title' : 'Development Manager'
        }

+ Response 201 (application/json)

        {
            newly created user object
        }

### /{user-id} [PUT]

+ Request (application/json)

        {
            'first_name' : 'Alvin',
            'last_name' : 'Pacot',
            'email' : 'sample@email.com',
            'password' : 'securepassword',
            'telephone' : '+1323453234',
            'password_repeat' : 'securepassword',
            'group_name' : 'Managers',
            'job_title' : 'Development Manager'
        }

+ Response 200 (application/json)

        {
            updated user object
        }

### /{user-id} [DELETE]

+ Response 200 (application/json)

        {
            'User is successfully deleted.'
        }

+ Response 500 (application/json)

        {
            'Failed to delete user.'
        }