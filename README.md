## Digital-Ocean-PHP-script-backup:

Simple PHP script to snapshot droplets daily and weekly.

#### How it works?

The script simply snapshots the droplets daily and delete the old ones except for the Friday backup. This means that Friday backups are kept unless they are 7 days older.

#### How to implement?

All you need to do is:

1- Change the token in token function.

2- Run a daily cronjob.

3- That's all.

#### Open your terminal and type:

```
php droplets.php
```
