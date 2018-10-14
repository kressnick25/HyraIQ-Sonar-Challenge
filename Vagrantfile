# -*- mode: ruby -*-
# vi: set ft=ruby :

$script = <<SCRIPT

sudo phpdismod xdebug; sudo service php7.2-fpm restart #xoff
cd /srv/www/app/current
make linkvar
make validate-composer
make dev

# Install phpunit-bridge dependencies
bin/phpunit --version
SCRIPT

Vagrant.configure("2") do |config|
  config.vm.box = "banshee"
  config.vm.hostname = "sonar.local"

  config.vm.network "private_network", ip: "10.1.0.2"
  config.vm.synced_folder "./", "/srv/www/app/current", type: "nfs", mount_options: ["tcp", "actimeo=2"]
  config.vm.provision "shell", inline: $script, privileged: false

  config.vm.provider "virtualbox" do |vb|
    vb.name = "sonar"
    vb.memory = 2048
  end

end
