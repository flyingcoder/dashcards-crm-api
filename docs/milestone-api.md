# Base URL [api.bizzooka.ca]

## milestone API [api/milestone]

### /{milestone-id} [DELETE]

+ Response 200 (application/json)

        {
            'milestone is successfully deleted.'
        }

+ Response 500 (application/json)

        {
            'Failed to delete milestone.'
        }

### /{milestone-id} [GET]

+ Response 200 (application/json)

        {
            //milestone object
        }

### /{milestone-id} [PUT]

+ Request (application/json)

        {
            'title' : 'SEO Milestone 1',
            'status' : 'Pending',
            'days' : 4

        }