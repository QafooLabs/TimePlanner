# Time Planner

Repository with example time planning application.

> *Disclaimer: This is not an official Qafoo product but a prototype. We don't
> provide support on this repository.*

This software is used as a demo project as well as internal software to manage
the very basic time planning concerns in Qafoo.

## Starting

The one-liner to get it all up and running – for more details check out the
text below:

    git clone https://github.com/QafooLabs/TimePlanner.git && cd TimePlanner && git submodule update --init && ant bootstrap && ant serve

### Detailed Instructions

Make sure the GIT submodules are checked out – you might want to run the
following command to initialize them inside the checkout:

    git submodule update --init

The prerequisites for this projects are:

* PHP >= 5.5

* A database: MySQL, SQLite or CouchDB

  Create a custom `environment.local` file with your customization of the
  `environment` file. You can look at `tests/environment.*` for examples. By
  default SQLite will be used with a database named `timeplanner.db` in the
  project root.

To initialize the database (database, schema and some default users) run the
following command once – should probably not be executed on the production
server:

    ant bootstrap

To run the project just execute the following command – this can be run each
and every time you use or develop the project again:

    ant serve

Then open the URL echo'ed on the command line, and you should be fine. All
required project initializations should already be included.

## Testing

To run all tests and checks just run:

    ant

This initializes the project and runs all tests and verifications.

## Deployment

We are using a very simple deployment method right now, by basically running a
`git pull` on a target system over ssh and then run `ant prepare` to ensure all
dependencies are updated. To use this configure the following two variables in
your `environment.local` file:

    deploy.host=ssh-target.host
    deploy.path=TimePlanner/

Then you can just run the following command:

    ant deploy

While the deployment runs on the target host a maintenance page will be
displayed. The command does not care about running database migrations or
something alike.

## About

This software features the following:

* Manage vacation per user

* Manage public holidays

  Public holidays can be imported from ICS files, for example.

* Manage "jobs" per month

  We use this to get a rough overview on everybody's occupancy as well as a very
  rough overview over a months income.

The software might or might not fit your usage patterns. We do not plan to
develop this as a generic time planning software solution. You might be able to
adapt it to your usage patterns, but it is very unlikely that we want to merge
your changes.
