# Smashing Image Compressor #

This little utility allows the compression of images.

## Dependencies ##

Dependencies should be installed, if not, please process as follow. (automation script coming!)

### Ruby ###

Ruby is nice. And comes with a lot of nice utilities. The best options is to run ruby through rbenv, by installing rbenv system wide. The following instructions are for Debian-related operating systems. (based on https://gist.github.com/jnx/1256593)

First, go root by typing

    sudo -i
    
or

    su -

Next, update, upgrade and install the basic tools

    apt-get update
    apt-get -y upgrade
    apt-get -y install build-essential git-core
 
Install rbenv by cloning it.

    git clone git://github.com/sstephenson/rbenv.git /usr/local/rbenv
 
Add rbenv to the path globally:

    echo '# rbenv setup' > /etc/profile.d/rbenv.sh
    echo 'export RBENV_ROOT=/usr/local/rbenv' >> /etc/profile.d/rbenv.sh
    echo 'export PATH="$RBENV_ROOT/bin:$PATH"' >> /etc/profile.d/rbenv.sh
    echo 'eval "$(rbenv init -)"' >> /etc/profile.d/rbenv.sh
 
    chmod +x /etc/profile.d/rbenv.sh
    source /etc/profile.d/rbenv.sh
 
Install ruby build

    sudo git clone https://github.com/sstephenson/ruby-build.git ~/.rbenv/plugins/ruby-build

Install the latest ruby and rehash

    rbenv install 2.0.0-p247
    rbenv global 2.0.0-p247
    rbenv rehash

## Image optim ##

First install the gem:

    sudo gem install image_optim
    
Next install its dependencies

    sudo apt-get install -y advancecomp gifsicle jpegoptim libjpeg-progs optipng pngcrush
 
## Dropbox ##

First install dropbox system-wide:

    sudo 'cd /usr/local/ && wget -O - "https://www.dropbox.com/download?plat=lnx.x86_64" | tar xzf -'
    sudo 'mv .dropbox-dist dropbox'
    
Next install dropbox CLI
    
    sudo 'wget -O /usr/local/bin/dropbox "http://www.dropbox.com/download?dl=packages/dropbox.py"'
    sudo 'chmod 755 /usr/local/bin/dropbox'
    
    # Creating the Dropbox (will be create in php_user home — it better exists!)
    # Usually, it'll be /var/www.
    run 'dropbox start'