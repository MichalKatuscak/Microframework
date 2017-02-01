Microframework
==============

"For superfast using!" Michal Katuščák

Docker
------

    docker build -t app-lamp .
	docker run -p 8080:80 -it --name my-app -v $PWD/app:/var/www/html app-lamp

