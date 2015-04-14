# Time Planner

Repository with example time planning application.

> *Disclaimer: This is not an official Qafoo product but a prototype. We don't
> provide support on this repository.*

This software is used as a demo project as well as internal software to manage
the very basic time planning concerns in Qafoo.

## Starting

Make sure the GIT submodules are checked out – you might want to run the
follwing command to initialize them:

    git submodule update --init

To run the project just execute the following command:

    ant serve

The open the URL echoed on the command line, and you should be fine. All
required project initializations should already be included. The prerequisites
for this projects are:

* PHP >= 5.5

* A running CouchDB server

## Testing

To run all tests and checks just run:

    ant

This initializes the project and runs all tests and verifications.

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
