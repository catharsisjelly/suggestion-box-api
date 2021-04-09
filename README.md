# Suggestion Box API

An idea I had to build for the Improv community. In Improv we usually take in suggestions from the audience to help
inspire the players in their scene or game. I wanted to build a universal API that could then be attahed to a website
and mobile app. The results could also be streamed live to an audience.

## Object relationships

* Box - A box contains one or more suggestion types and has many suggestions
* Suggestion Type - A Suggestion type has many Suggestions and belongs to a Box. This is the type of suggestion you might want e.g. Location, Genre, Event 
* Suggestion - A suggestion is belongs to a Box and a Suggestion Type, it can be discarded

## Actors

Below is information on the kind of people (or Actors) that will use this API and how they will use it

* Creator - A person who wants to create a Suggestion Box to collect suggestions
* Participant - A person who wants to add a Suggestion to a Suggestion Box
* Audience - Someone who is not participating but wants to see the Suggestions being made

## Build information

Ensure that you have [PHP composer](https://getcomposer.org/) (v2) installed

This is built using PHP and API Platform, please feel free to add code to it i=to make it better overall. There is a
docker container that you can use to run a local development. If you have any problems/questions with this then please
file an issue and or a pull request making the relevant changes.

For a list of tasks see the [Github Issue list](https://github.com/catharsisjelly/suggestion-box-api/issues) 

## Development setup

If your running docker you can access the API documentation via `0.0.0.0:4430/api/docs`. 
Remember to create a file called `.env.test.local` and add the following line to it for the behat tests to work with
the supplied DB test container

`DATABASE_URL="mysql://root:test@db_test:3306/suggestion-box-test"`

You can then run the following to set up your DB

```bash
docker-compose up -d
docker-compose exec php sh -c "composer install"
docker-compose exec php sh -c "bin/console doctrine:migrations:migrate -n"
docker-compose exec php sh -c "bin/console doctrine:migrations:migrate -e test -n"
```

### Running Behat tests locally

You can do this inside the container, run the following command

```bash
docker-compose exec php sh -c "APP_ENV=test bin/behat"
```
