set :deploy_to, "/var/www/tools.smashingmagazine.com/root/"

set :application, "tools-root"
set :deploy_via, :copy
#set :copy_cache, true
set :copy_exclude, [".git/*", "**/._*", "**/.DS_Store"]

set :repository, "public"
set :scm, :none
set :copy_compression, :gzip
set :use_sudo, false
set :domain, 'tools.smashingmagazine.com'
set :user, 'deploy'
set :port, 122

role :web, 'tools.smashingmagazine.com'