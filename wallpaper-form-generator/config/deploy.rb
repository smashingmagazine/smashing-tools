task :production do
  set :deploy_to, "/var/www/tools.smashingmagazine.com/wallpaper-generator"
  set :domain, 'tools.smashingmagazine.com'
  role :web, 'tools.smashingmagazine.com'
end

task :staging do
  set :deploy_to, "/var/www/playground.smashingmagazine.com/wallpaper-generator"
  set :domain, 'playground.smashingmagazine.com'
  role :web, 'playground.smashingmagazine.com'
end

set :application, "wallpaper-form-generator"
set :deploy_via, :copy
#set :copy_cache, true
set :copy_exclude, [".git/*", "**/._*", "**/.DS_Store"]

set :repository, "build"
set :scm, :none
set :copy_compression, :gzip
set :use_sudo, false
set :user, 'deploy'
set :port, 122

desc "Run tasks in production enviroment."
task :first do
  set :copy_exclude, [".git/*", "**/._*", "**/.DS_Store"]
end

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