# DB Watcher

So officially many sites which handle card transactions indirectly or directly.
Should have file integrity monitoring on their servers, with the use of tools like OSSEC.
However many attackers these days are inserting card detail skimming javascript via the database rather
than the traditional modify an template file on the servers.

For example on Magento there has been a spate of attacks where such javascript was inserted in to the config table 
under the Miscellaneous Scripts. File integrity tools will simply not pick up on these attacks.

Which is the reason for this tool. Which gives you the ability to monitor key tables such as config or cms tables,
so that when an attacks occurs you can be alerted. You can remove the offending javascript, with the time frame provided
by DB Watcher you can check your web access logs to see what attack vector was used.

This software uses the [Maxwell](https://github.com/zendesk/maxwell) to connect to mysql and read the mysql 
binlog for any query which adds or changes data and outputs json to Kefka and files.
For the moment DB Watcher uses the file mode via named pipe.

The system allows you to set up watch tasks which can have the following rules:
- Watch for specific DDL operations such as insert, update or delete operations
- Watch specific database tables for changes
- Watch specific database for any change events

In a single task these three rules can be chained together in any combination.

A task can have actions which are executed if all the task rules are matched.
At the moment DB Watcher has one action which is logger since we also happen to be using
[Monolog](https://github.com/Seldaek/monolog)

## Installation

* Download the code either via a git clone operation or http download from github
* Run composer install in the root directory
* Download the latest Maxwell release at https://github.com/zendesk/maxwell/releases
* Follow maxwell quick start guide [here](http://maxwells-daemon.io/quickstart/) until you get to the 'Mysql permissions' section
* After doing the 'Mysql permissions' section goto https://github.com/zendesk/maxwell/blob/master/src/main/resources/sql/maxwell_schema.sql and run that mysql into the maxwell table you have created
* Test the maxwell system by following the steps in the 'STDOUT producer' of the quick start to confirm changes are outputed on to the cli
* Create named fifo pipe by running either or the following:
```
# mkfifo /path/to/pipe
or
# mknod /path/to/pipe p
```
* Then configure DB Watcher rule by creating a tasks.yaml rule file in the conf directory. You can use tasks.example.yaml to show you how to configure
* Then configure the DB Watcher logger by creating container.conf.php file in the conf directory. Please see container.conf.example.php for examples however you are able to use any monolog handler [here](https://github.com/Seldaek/monolog/blob/master/doc/02-handlers-formatters-processors.md#send-alerts-and-emails)
* Then run maxwell using:
```
/path/to/maxwell/bin/maxwell --user='maxwell' --password='XXXXXX' --host='127.0.0.1' --producer=file  --output_file /path/to/pipe
```
* Then run DB watcher using
```
php /path/to/dbwatcher/bin/dbwatcher.php run /path/to/pipe


```

