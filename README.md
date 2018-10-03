# NazBits 2015 website

I found this source code on Heroku and decided to put it here as a historical reference.

This website was initially created to host the first NazBits iClinic event back in 2015.
It was later extended to also host lesson plans and resources of some of the mini "courses"
organised and held by NazBits on campus.

The website is currently (still) hosted on Heroku [https://nazbits.herokuapp.com](https://nazbits.herokuapp.com).

The site is made up of two main applications:

### iClinic
The first is the iClinic app, which is on the homepage. It was used during
the first iClinic to allow anyone on campus to post and upvote issues they were facing on their laptops/devices
as well as to "book" an antivirus. This allowed us to know in advance which problems we would need
to fix and to know which antivirus was in demand so that we could buy the necessary licenses.

The app has small PHP backend and an AngularJS (1.x) front-end and uses a PostgreSQL database.
It expects the database tables to be set up manually (no migrations) and expects
a `DATABASE_URL` env variable to hold the database url.
The frontend scripts are located in the `public` directory, and the bulk of the backend
is located in the `Lib.php` file on the root. This file contains abstractions for working with sessions,
database, requests and models. The requests are handled in the `public/handle.php` file.

### Tutorials
This app contains info and lesson plans about some of the trainings we held. It's pretty much
and indepent app that was squeezed on the website cause I was too lazy to create a separate project/hosting
for it, or to create a proper homepage for the NazBits website with links to different sections.

To access the app, you have manually enter the `/tutorial`
path in the url, i.e. [https://nazbits.herokuapp.com/tutorials](https://nazbits.herokuapp.com/tutorials)

It's an AngularJS (It was the thing back then) app without any server-side logic.
The data on the different trainings is stored in JSON files.
The source code for this app is in the `public/tutorials` directory.
