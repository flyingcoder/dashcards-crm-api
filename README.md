FORMAT: 1A
HOST: http://polls.apiblueprint.org/

# Buzzooka CRM API Docs

Buzzooka crm api documentation

## Timer Collection [api/timer/{action}]

### Start/Stop General Timer of the company [POST]

+ Request (application/json)

        {
            'subject_type' => 'App\Company'
        }

+ Response 200 (application/json)

        [
            {
                * timer object
            }
        ]

## Timer Collection [api/timer/task/{action}]

### Start/Stop Task Timer of the company [POST]

+ Request (application/json)

        {
            'subject_type' => 'App\Task',
            'subject_id' => 1, //task id
            'description' => 'Task Timer'
        }

+ Response 200 (application/json)

        [
            {
                * timer object
            }
        ]

## Invoice Collection [api/invoice]

### List All invoices of the company [GET]

+ Response 200 (application/json)

        [
            {
                "total": 50,
                "per_page": 15,
                "current_page": 1,
                "last_page": 4,
                "first_page_url": "http://laravel.app?page=1",
                "last_page_url": "http://laravel.app?page=4",
                "next_page_url": "http://laravel.app?page=2",
                "prev_page_url": null,
                "path": "http://laravel.app",
                "from": 1,
                "to": 15,
                "data": [
                    {
                        "* all invoice data"
                    }
                ]
            }
        ]
        
### Create new invoice [POST]

+ Response 200 (application/json)

## Invoice Collection [api/invoice/{id}]

### Return single invoice [GET]

+ Response 200 (application/json)

    {
        "* invoice fields to be added"
    }
        

## Project Collection [api/project]

### List All project of the company [GET]

+ Response 200 (application/json)

        [
            {
                "total": 50,
                "per_page": 15,
                "current_page": 1,
                "last_page": 4,
                "first_page_url": "http://laravel.app?page=1",
                "last_page_url": "http://laravel.app?page=4",
                "next_page_url": "http://laravel.app?page=2",
                "prev_page_url": null,
                "path": "http://laravel.app",
                "from": 1,
                "to": 15,
                "data": [
                    {
                        "* all project data"
                    }
                ]
            }
        ]
        
### Create new Project [POST]

+ Response 200 (application/json)
        "* will add response message later"
        
## Project Collection (Current User) [api/project/{id}/mine]

### List All project of the company [GET]

+ Response 200 (application/json)

        [
            {
                "total": 50,
                "per_page": 15,
                "current_page": 1,
                "last_page": 4,
                "first_page_url": "http://laravel.app?page=1",
                "last_page_url": "http://laravel.app?page=4",
                "next_page_url": "http://laravel.app?page=2",
                "prev_page_url": null,
                "path": "http://laravel.app",
                "from": 1,
                "to": 15,
                "data": [
                    {
                        "* all project data"
                    }
                ]
            }
        ]
        
## Project Tasks Collections [api/project/{id}/tasks]

### List All Task in a project [GET]

+ Response 200 (application/json)

        [
            {
                "total": 50,
                "per_page": 15,
                "current_page": 1,
                "last_page": 4,
                "first_page_url": "http://laravel.app?page=1",
                "last_page_url": "http://laravel.app?page=4",
                "next_page_url": "http://laravel.app?page=2",
                "prev_page_url": null,
                "path": "http://laravel.app",
                "from": 1,
                "to": 15,
                "data": [
                    {
                        "assignee": "lastname, firstname",
                        "id": 1,
                        "title": "string",
                        "* all task column"
                    }
                ]
            }
        ]
        
+ Response 403
        if user is not an admin

### Create new task in project HQ [POST]

+ Request (application/json)

        {
            "* task fields here",
        }

+ Response 201 (application/json)

    + Headers

            Location: /questions/2

    + Body

            {
                "* task is created message" (sometimes nothing)
            }
            
## Project Tasks Collections (Current User) [api/project/{id}/tasks/mine]

### List All task of a project per current user [GET]

+ Response 200 (application/json)

        [
            {
                "total": 50,
                "per_page": 15,
                "current_page": 1,
                "last_page": 4,
                "first_page_url": "http://laravel.app?page=1",
                "last_page_url": "http://laravel.app?page=4",
                "next_page_url": "http://laravel.app?page=2",
                "prev_page_url": null,
                "path": "http://laravel.app",
                "from": 1,
                "to": 15,
                "data": [
                    {
                        "assignee": "lastname, firstname",
                        "id": 1,
                        "title": "string",
                        "* all task column"
                    }
                ]
            }
        ]

