# Base URL [api.bizzooka.ca]

## Clients API [api/user/clients]

## Clients view API [api/user/clients?view={grid|list}] default view "list"

## Clients sort API [api/user/clients?sort={column}|{asc|desc}]

## Clients search API [api/user/clients?search={keyword}]

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
            'telephone' : '+1323453234',
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