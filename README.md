# WEB CRAWLER IN PHP

## PURPOSE
- This project is for the assignment of "Cryptography and Network Security" Course at HCMUT.
- In this project, we tried to implement a simple Web Crawler in PHP which scrape and display the data based on the URL and file extension provided by user. The project also allows user to directly download all scraped data.
## PREREQUISITE
- [ ] [Docker](https://www.docker.com/)

## CONFIG ENVIRONMENT
- First, clone this repository into htdocs
```
git clone https://github.com/datphan-cs/PHP-Crawler
```
- Then enter below command to setup the environment
```
cd PHP-Crawler
docker build -t php_env .
```
## EXECUTE THE CODE
- First, run the docker image
```
docker run -p 8080:80 php_env
```
- Then use your browser and browse to the following URL:
```
http://localhost:8080
```
