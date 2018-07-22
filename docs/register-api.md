# Base URL [api.bizzooka.ca]

## Authentication API [api/register]

### Signup [POST]

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