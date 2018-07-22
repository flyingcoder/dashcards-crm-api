# Base URL [api.bizzooka.ca]

## Dashitems API [api/dashitems]

### / [GET]

+ Response 200 (application/json)

        {
            [
                {
                    "id":1,
                    "name":"Tasks",
                    "slug":"tasks",
                    "description":"",
                    "type":"",
                    "deleted_at":null,
                    "created_at":"2018-07-19 18:22:57",
                    "updated_at":"2018-07-19 18:22:57"
                },
                {
                    "id":2,
                    "name":"Timeline",
                    "slug":"timeline",
                    "description":"",
                    "type":"",
                    "deleted_at":null,
                    "created_at":"2018-07-19 18:22:57",
                    "updated_at":"2018-07-19 18:22:57"
                }
            ]
        }

### default/dashitems [GET]

+ Response 200 (application/json)

        {
            'user' : 
                {
                    'user-object'
                }
        }

## Authentication API [api/dashboard/]

### default/dashitems [POST]

+ Request (application/json)

        {
            'company_name' : 'Facebook'
            'company_email' : 'test@gmail.com'
            'first_name' : 'Alvin'
            'last_name' : 'Pacot'
            'email' : 'sample@email.com',
            'password' : 'securepassword'
        }

+ Response 200 (application/json)

        {
            'token' : 'eyJ0eXasAiO-so-on'
            'user' : 
                {
                    'user-object'
                }
        }

### default/dashitems [GET]

+ Response 200 (application/json)

        {
            'user' : 
                {
                    'user-object'
                }
        }