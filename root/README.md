# Root placeholder page for tools.smashingmagazine.com #

This little utility allows you to generate a properly formated form, only by specifying your theme information.

## Dependencies ###

### Capistrano ###

Assuming you are using OS X, you will need [Capistrano](https://github.com/capistrano/capistrano) to deploy the app. The best way to do this is to install it as a gem, which is remarkably simple given Ruby is available out-of-box on OS X. Just runs the following command:

	$ gem install capistrano

Note: If you are using rbenv ou RVM, then you'll now what to do!

## How to deploy ##

Just run this command:

    cap deploy