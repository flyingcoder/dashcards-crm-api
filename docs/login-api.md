# Base URL [api.bizzooka.ca]

## Authentication API [api/login]

### Login [POST]

+ Request (application/json)

        {
            'email' : 'sample@email.com',
            'password' : 'securepassword'
        }

+ Response 200 (application/json)

        {
            'success' : 
                { 
                    'token' : 'eyJ0eXAiO-so-on'
                }
            'user' : 
                {
                    'user-object'
                }
        }