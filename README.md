# WatchListForEvE
Crest tool with JS/HTML and mainly PHP for watchlisting Groups and Supers of Groups. Also contains a prototype for a local spam filter.
The files with number do the database pulls.
Note: This is one of my first programms, nothing is to be taken good!

General Files:

sql.php (DB connector)

Crest.php (crest connector)


Supercapital files are:

FetchSuperKills_1.php get all new (not in DB) kills from ZKB

writeSuperPilotsAlliance_2.php gets from the EVE API the actual Alliance/Corp for all not in DB Super pilots

getLastKillsByPilot_3.php gets last kill from this pilot and checks if he is in a super (no loss/no kill in other ship as last kill/loss)

addSuperCapPilot.php the actual script to read pilots from DB and post to Crest

getSuperPilots.php returns some stats; only for testing

SuperCapWatchlist.html frontend for the tool

GroupWatchlist files are:

addCorperationMembers.php gets EveWho data of group and posts to crest

GroupWatchlist.html Frontend for the tool

LocalSpam files are:

LocalChatScan.php

Local.txt

If you have questions feel free to contact me.
EvE : Shegox Gabriel
reddit: Shegox
