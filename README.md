# Heros Application

V2 is running at http://3.144.142.248/

### This is the v2-localcache branch install instructions are different than v1
git 
Open in  browser: http://localhost:8000 !!

NOT!  http://127.0.0.1:8000

To install this application clone the repo

You should be running php 8.2 with all extensions required to run a symfony application for help see symfony documentation

You should be running close to npm 10.8.2 and node v20.17.0 not tested on other versions.

To simplify set up this simply uses sqlite3 so no database set up is needed.

To run the application put a marvel api key in the .env file first

run cp .env.example .env  and then fill in the keys.

Open 2 terminals at root of application

Terminal 1:
run: npm update

run: cd tools/php-cs-fixer

run: composer update

run: cd ../../

then run: npm run dev

this will serve front end and compile assets

Terminal 2:

run: composer update

run: php bin/console make:migration

run: php bin/console doctrine:migrations:migrate

* Command below loads the db for marvel characters

run: php bin/console app:fetch-marvel-characters

then run: composer serve

#### Using these commands to start the front and back ensures the application is running on correct ports to communicate!!

### Make sure to access the application from http://localhost:8000/ and not http://127.0.0.1:8000 or you will get CORS errors!

#### The about page of the application lists some helpful developer commands for formatting, linting and running tests.
Home
![Alt text](./home.png)
Heros
![Alt text](./heros.png)
About
![Alt text](./about.png)
