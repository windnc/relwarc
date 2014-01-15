#!/bin/bash

rm -rf cookie.txt

curl --cookie-jar cookie.txt --output /dev/null \
		--referer "http://www.hi-bogo.net" \
		 http://www.hi-bogo.net/cdsb/login_process.php


curl --cookie-jar cookie.txt \
	 --cookie cookie.txt \
	 --data 'mode=login' \
	 --data 'kinds=outlogin' \
	 --data 'user_id=windnc' \
	 --data 'save_id=Y' \
	 --data 'passwd=abcd1234' \
	 --location \
	 --output out.html \
		 http://www.hi-bogo.net/cdsb/login_process.php

#curl --cookie cookie.txt \
#		--output "result.html" \
#		"http://www.hi-bogo.net/cdsb/board.php?category=&board=kentertain&search=subject&keyword=%EB%AC%B4%ED%95%9C%EB%8F%84%EC%A0%84+130216"

#curl --cookie cookie.txt \
#		--referer "http://www.hi-bogo.net" \		
#		--output "result2.html" \
#		"http://www.hi-bogo.net/cdsb/board.php?board=kentertain&bm=view&no=53088&category=&auth=&page=1&search=subject&keyword=%EB%AC%B4%ED%95%9C%EB%8F%84%EC%A0%84+130216&recom="


curl --cookie cookie.txt \
		--referer "http://www.hi-bogo.net/cdsb/download.php?down=1361183598_1.torrent|||kentertain|||%EB%AC%B4%ED%95%9C%EB%8F%84%EC%A0%84.E317.hdtv.x264-HAVC.torrent|||201302|||Y" \
		--output "result3.torrent" \
		"http://www.hi-bogo.net/cdsb/download.php?down=1361015340_1.torrent|||kentertain|||%EB%AC%B4%ED%95%9C%EB%8F%84%EC%A0%84+130216+HDTV+x264+720p-%E5%85%89%E9%A2%A8.avi.torrent|||201302|||Y"

echo "a"

#--referer "http://www.hi-bogo.net/cdsb/download.php?down=1361183598_1.torrent|||kentertain|||%EB%AC%B4%ED%95%9C%EB%8F%84%EC%A0%84.E317.hdtv.x264-HAVC.torrent|||201302|||Y" \
