# Smashing Tools #

This repo holds all the small utilities needed to make smashing soo smashing!

## Tools ##

- Root placeholder page for tools.smashingmagazine.com
- Smashing Form Generator _for_ Monthly Wallpaper Calendars
- Smashing image compressor

## Dependencies ##

Dependencies are a pain in the ass, they are need for every tools (for the deployment chain). On OS X, most of those tools should normally be already installed, if you haven't messed up with Boxen.

### OS X ###

First, we assume you're using OS X. I you dont, you won't be able to compress images while deploying the app, as we are using [ImageOptim-CLI](https://github.com/JamieMason/ImageOptim-CLI).

Dependencies over OS X only softwares will be removed in further versions.

### ImageOptim-CLI ###

Installing ImageOptim-CLI is very simple. You can just install it with npm.

	$ npm install -g imageoptim-cli

If you don't have npm installed, just install it using:

	$ brew install nodejs

### Google Closure ###

You can install google closure using homebrew. Just run:

	$ brew install closure-compiler
	
Closure, as well as YUI Compressor, will need a Java VM to work.

### YUI Compressor ###

You can install google closure using homebrew. Just run:

	$ brew install yuicompressor

### Capistrano ###

Assuming you are using OS X, you will need [Capistrano](https://github.com/capistrano/capistrano) to deploy the app. The best way to do this is to install it as a gem, which is remarkably simple given Ruby is available out-of-box on OS X. Just runs the following command:

	$ gem install capistrano

Note: If you are using rbenv ou RVM, then you'll now what to do!

### PHP/Apache Stack ###

PHP and Apache come bundled with Mac OS X.