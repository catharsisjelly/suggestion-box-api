# Suggestion Box API

An idea I had to build for the Improv community. In Improv we usually take in suggestions from the audience to help
inspire the players in their scene or game. I wanted to build a universal API that could then be attahed to a website
and mobile app. The results could also be streamed live to an audience.

## Build information

This is built using PHP and API Platform, please feel free to add code to it i=to make it better overall. There is a
docker container that you can use to run a local development. If you have any problems/questions with this then please
file an issue and or a pull request making the relevant changes.

## Todo

This todo is in no particular order

* Fix Github actions to run behat & PHPUnit tests
* Add in postman calls
* Add in more Behat tests
* Add in some sort of login or thing that only allows the person who started the box to edit the box
* Add in voting for suggestions
* Add in moderation for suggestions
* Add in a check so that suggestions can onl happen between the `startDatetime` and `endDatetime`

## Development setup

If your running docker you can access the API documentation via `0.0.0.0:4430/api/docs`. 
Remember to create a file called `.env.test.local` and add the following line to it for the behat tests to work with
the supplied DB test container

`DATABASE_URL="mysql://root:test@db_test:3306/suggestion-box-test"`

You can then run the following to set up your DB

```bash
docker-compose up -d
docker-compose exec php sh -c "bin/console doctrine:migrations:migrate -n"
docker-compose exec php sh -c "bin/console doctrine:migrations:migrate -e test -n"
```

### Running Behat tests locally

You can do this inside the container, run the following command

```bash
docker-compose exec php sh -c "APP_ENV=test bin/behat"
```
