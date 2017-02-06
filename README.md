# RAMON :
## Reachable Applications Monitor Over Network


PRE-INSTALL:
```
# for minimal containers:
apt-get install curl nano wget links git 

# for all people, if you want to use php5, need to change to 5
apt-get install php7.0-cli php7.0-curl php7.0-mysql mysql-server
```

APP:
```
git clone https://github.com/sgregori/ramon/
mysql -u root -p ramon < db.sql
```

TELEGRAM, CHAT_ID & API TOKEN:
```
- Contact to BotFather
- Ask BotFather for a new bot ( need a botname and bot username )
- Save the obtained TOKEN in to the config.
- Create a new group, add your bot to the group.
- Add also the bot named "@get_id_bot" ( its safe and you can remove it when your bot is working ).
- Write in the group:  /my_id@get_id_bot
- And save the CHAT_ID to the config.
```

CRON (new job):
```
* * * * * root php /home/ramon/Bot.php &> /dev/null
```

## Reachable Applications Monitor Over Network

