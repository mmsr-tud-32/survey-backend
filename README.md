# Survey backend

## Setup

Create a user `mmsr` with password `mmsr` and a database `survey` on mysql. Or add a `.env.local` file which overrides the connection string to point to a different database.

### Migrations

```
php bin/console doctrine:migrations:migrate
```

### Dev server

```
symfony server:start
```

### Google App Engine

Copy `app.yaml.sample` to `app.yaml` and fill in the values.

Create a mysql database in Google cloud and fill in the values in the `DATABASE_URL`.

A default bucket should be created when the app is deployed with the same name as the project e.g. `mmsr-survey.appspot.com`.

Remove the `scripts` section from `composer.json`.

Run `gcloud app deploy`.

See the [Community Tutorial](https://cloud.google.com/community/tutorials/run-symfony-on-appengine-standard) on running Symfony in App Engine for more info.

## API

### Survey
#### POST /survey

Requires `title`, `description`.

Returns the created survey

#### GET /survey/{id}

Returns the description title, description and images.

#### POST /survey/{id]/images

Add a new image to the survey

### Submission
#### POST /submission

Requires `name` and uuid of survey.

Returns id of the created submission and a list of images for practise and reals.

#### GET /submission/{submission_uuid}

Returns the submission with progress

#### POST /submission/{submission_uuid}/{question_uuid}

Set the answer on a question

#### POST /submission/{submission_id}/submit

Mark a submission as submitted.

## Models

### Survey

A survey category allowing for multiple different surveys.

|||
|---|---|
|id|int
|uuid|uuid
|title|string

### SurveyImage

An image in a survey. Containing a boolean to determine if the image is a fake.

|||
|---|---|
|id|int
|survey|int
|uuid|uuid
|fake|bool

### SurveySubmission

A submission by a user.

|||
|---|---|
|id|int
|uuid|uuid
|name|string
|submitted|bool

### SurveySubmissionImage

A single answer to a survey.

|||
|---|---|
|id|int
|survey_id|int
|image_id|int
|fake|bool
