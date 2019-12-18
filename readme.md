# PocketServer

Pocket Server is a tool developed using Key PHP Framework that helps you
create your own TCP Server. It's completely customizable. You can make
your own derivation by changing the different classes that comes within the
bundle.

This bundle is under MIT License
Please refer to the git in order to access to full details.

## How it works?

In order to download the project type

```git clone https://github.com/PYLOTT/PocketServer```

Wait for the download to complete.
Once done, type in

```php ENV.php```

You can output the log in a file with

```php ENV.php > logs/latest.log```

Or

```php ENV.php >> logs/latest.log```

To have the previous outputs.

Default access: http://localhost:8086

## Known issues

When you stop the server you will have to wait a bit before restarting it.
If you do not do so, the server will output an error and will try to restart
Until the maximum try counter has reached it's limit.

## Tests to trigger

We have to try outputting a file when triggering a request that refer to one.
Please note that this server is a project made out of KEYPHP Framework - also
Developed by Louis Bertrand <adressepro111@pylott.yt> - and both are in unstable
mode. Error throwing can be a recurring problem.

To help us trying to avoid problems, please create an issue in the corresponding
Tab on the git.

`LOUIS BERTRAND <ADRESSEPRO111@PYLOTT.YT> © 2019`
