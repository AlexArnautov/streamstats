<p align="center">
    <h1 align="center">StreamStats application</h1>
    <br>
</p>

This application is aimed at helping Twitch viewers get a quick look at how the channels they watch compare to the top 1000 live streams.

REQUIREMENTS
------------
Docker installed on host system.

INSTALLATION
------------
1) Clone the repository to any folder
2) Run: ". build.sh" in cloned repository folder 
3) Go to http://localhost/

Bash script will up docker containers, run migrations and frontend build.
After that script will start retrieve streams via the Twitch API.

DIRECTORY STRUCTURE
-------------------

      commands/             contains console commands (controllers)
      config/               contains application configurations
      controllers/          contains Web controller classes
      models/               contains model classes
      runtime/              contains logs, cache
      components/           contains helpers, repositories, services, interfaces
      web/                  contains the entry script and Web resources
      web/js/streamstats/   contains the Vue application
      docker                contains docker images and configs
      migrations            contains DB migrations

Setup cron task in (example Ubuntu):
-------------------
1) nano /etc/crontab
2) add string to the file:
   */15 * * * * /usr/bin/php /streamstat_folder/yii parse-streams

NOTES
-------------------
1) Main goal and priority was to develop real life application. Application
   should look like mini startup for the people: nice and simple. I used Twitter Boostrap 4 and FontAwesome 6
   icons for the design purposes.
2) I choose the Yii2 because it is a little faster for me to develop right now.
All the approaches are mostly the same as in Laravel. ActiveRecord pattern, MVC e.t.c.
3) Refresh streams can be run by command: php yii parse-streams
Command controller: commands/ParseStreamsController.php
4) Vue app is initialised in the: views/site/index.php 

TO DO
-------------------
Because of lack of time for the task I had to prioritize things. I would like to mention
them. What should be done before deploy to production:

1) API Keys are stored in the repository in config file. This is not secure. Keys should be included via 
external file or using special storages like Vault.
2) In production ready environment we should use TDD: Unit Tests, Functional Tests
3) Same for Vue application. It pretty basic without and tests.
4) PHP Code Style fixer should be added https://github.com/FriendsOfPHP/PHP-CS-Fixer
5) Because we have every 15 minutes refresh we can add DB queries caching with TTL ~ 15min in memory 
storage like Redis.
6) We need to add check for console command to avoid running several copies in the same time.
If duration of command will become more than 15 minutes or in case of any issues.

