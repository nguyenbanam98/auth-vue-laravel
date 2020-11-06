1. Exams management

Resource API

MVCS: Model View Controller Service

Domain: localhost:8000

- List exams (include pagination)
    * URI: /api/v1/exams
    * Method: GET
    * Request: {domain}/api/v1/exams?limit=20&page=2&column=id&sort=desc
    * Response:
        Success:
            {
                "status": true,
                "code": 200,
                "data": [
                    {
                        "id": 3,
                        "name": "Sterling Torp V",
                        "created_at": "2020-03-26T15:12:25.000000Z",
                        "updated_at": "2020-03-26T15:12:25.000000Z"
                    },
                    ................................................
                    ................................................
                ],
                "meta": {
                    "total": 30,
                    "perPage": "2",
                    "currentPage": 2
                }
            }
        Faile:
            {
                "status": false,
                "code": 500,
                "message": "Undefined variable: examPaginates"
            }


- Create exams
    * URI: api/v1/exams
    * Method: POST
    * Request:
        {
            name: 'Exam name sample'
        }
    * Response:
        {
            "status": true,
            "code": 200,
            "data": {
                "id": 32,
                "name": "Exam Covid-20",
                "updated_at": "2020-03-29T14:21:16.000000Z",
                "created_at": "2020-03-29T14:21:16.000000Z"
            }
        }

- Update exams
    * URI: api/v1/exams/{id}
    * Method: PUT
    * Request:
        {
            name: 'Exam name sample'
        }
    * Response:
        Success:
            {
                "status": true,
                "code": 200,
                "data": {
                    "id": 32,
                    "name": "Exam Covid-20",
                    "updated_at": "2020-03-29T14:21:16.000000Z",
                    "created_at": "2020-03-29T14:21:16.000000Z"
                }
            }
        Faile:
            {
                "status": false,
                "code": 500,
                "message": "Message faile"
            }

- Show exams
    * URI: api/v1/exams
    * Method: GET
    * Request:
    * Response:

