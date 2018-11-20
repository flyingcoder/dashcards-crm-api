# Base URL [api.bizzooka.ca]

## Autocomplete API [api]

## Actions ['client', 'service', 'member']

//This will populate a project with milestone from a milestone template
### /projects/{project_id}/milestone-import [POST]

+ Request (application/json)
		{
			'template_id' => 1
		}

+ Response 200 (application/json)

        {
            // Results Object
        }

//Use this api to get all template for selection.
### /template?all=true

+ Response 200 (application/json)

        {
            // Results Object
        }