set :deploy_to, "/var/www/playground.smashingmagazine.com/smashing-compressor"
set :domain, 'playground.smashingmagazine.com'
role :web, 'playground.smashingmagazine.com'

set :application, "wallpaper-form-generator"
set :deploy_via, :copy
#set :copy_cache, true
set :copy_exclude, [".git/*", "**/._*", "**/.DS_Store"]

set :repository, "build"
set :scm, :none
set :copy_compression, :gzip
set :use_sudo, false
set :user, 'deploy'

set :php_user, 'www-data'

set :port, 122

default_run_options[:pty] = true

before 'deploy:update_code', "deploy:build_assets"

namespace :deploy do
  task :build_assets do
    run_locally 'rm -rf build/ && mkdir build'
    run_locally 'cp -r source/images source/includes source/index.php build'
    run_locally 'mkdir build/stylesheets build/javascripts'
    
    run_locally 'imageoptim -a -q -d source/images'
    
    run_locally 'closure-compiler --compilation_level WHITESPACE_ONLY --js source/javascripts/jquery-1.10.1.min.js --js source/javascripts/parsley.min.js --js source/javascripts/main.js --js_output_file build/javascripts/app.js'
    run_locally 'yuicompressor source/stylesheets/main.css -o build/stylesheets/app.css'
  end
end