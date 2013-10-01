set :deploy_to, "/var/www/playground.smashingmagazine.com/wallpaper-selection"

set :application, "smashing-wallpaper-selection"
set :deploy_via, :copy
#set :copy_cache, true
set :copy_exclude, [".git/*", "**/._*", "**/.DS_Store", "source_wallpapers/*"]

set :repository, "public"
set :scm, :none
set :copy_compression, :gzip
set :use_sudo, false
set :domain, 'playground.smashingmagazine.com'
set :user, 'deploy'
set :port, '122'

role :web, 'playground.smashingmagazine.com'

desc "Run tasks in production enviroment."
task :first do
  set :copy_exclude, [".git/*", "**/._*", "**/.DS_Store"]
end

namespace :database do
  task :setup do
    run "rm #{shared_path}/database/* && php #{current_path}/utils/database_setup.php"
    run "chmod g+rw #{current_path}/database/*"
  end
end

namespace :wallpapers do
  task :delete do
    run "rm -rf #{shared_path}/wallpapers/*"
  end
  
  task :delete_source do
    run "rm -rf #{shared_path}/source_wallpapers/*"
  end
  
  task :upload_source do
    run_locally "tar -czf /tmp/#{File.basename(release_path)}.tar.gz public/source_wallpapers/*"
    upload("/tmp/#{File.basename(release_path)}.tar.gz", "/tmp/#{File.basename(release_path)}.tar.gz")
    run "tar -xzf /tmp/#{File.basename(release_path)}.tar.gz -C #{shared_path}/source_wallpapers/"
    run_locally "rm /tmp/#{File.basename(release_path)}.tar.gz"
  end
end

before 'deploy:update_code' do
  run_locally 'rm -rf public/wallpapers/*'
  run_locally 'rm -rf public/database/*'
end

after 'deploy:setup' do
  run "mkdir -p #{shared_path}/database"
  run "mkdir -p #{shared_path}/wallpapers"
  run "mkdir -p #{shared_path}/source_wallpapers"
  
  run "chmod g+w #{shared_path}/database"
  run "chmod g+w #{shared_path}/wallpapers"
  run "chmod g+w #{shared_path}/source_wallpapers"
end

after 'deploy' do
  run "rm -rf #{release_path}/database"
  run "rm -rf #{release_path}/wallpapers"
  run "rm -rf #{release_path}/source_wallpapers"
  
  run "ln -nfs #{shared_path}/database #{release_path}/database"
  run "ln -nfs #{shared_path}/wallpapers #{release_path}/wallpapers"
  run "ln -nfs #{shared_path}/source_wallpapers #{release_path}/source_wallpapers"
  
  run "chmod g+w #{release_path}/wallpapers"
  run "chmod g+w #{release_path}/source_wallpapers"
  
  run "chmod a+x #{release_path}/*.php"
  run "chmod a+x #{release_path}/theme/*.php"
  run "chmod a+x #{release_path}/includes/*.php"
  
  run "chmod u+x #{release_path}/utils/*"
end