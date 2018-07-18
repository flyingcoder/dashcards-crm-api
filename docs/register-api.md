# Base URL [api.bizzooka.ca]

## Authentication API [api/register]

### Login [POST]

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
            'success' : 
                { 
                    'token' : 'eyJ0eXAiO-so-on'
                }
            'user' : 
                {
                    'user-object'
                }
        }