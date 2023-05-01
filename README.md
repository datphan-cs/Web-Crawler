# WEB CRAWLER IN PHP

## PURPOSE
- This project is for the assignment of "Cryptography and Network Security" Course at HCMUT.
- In this project, we tried to implement a simple Web Crawler in PHP which scrape and display the data based on the URL and file extension provided by user. The project also allows user to directly download all scraped data.
## PREREQUISITE

- [ ] [PHP](https://www.php.net/downloads.php) >= 7.4
- [ ] [XAMPP](https://www.apachefriends.org/)
- [ ] [Composer](https://getcomposer.org/download/)
## CONFIG ENVIRONMENT
- First, clone this repository into htdocs
```
git clone https://github.com/datphan-cs/PHP-Crawler
```
- Then enter below command to setup the environment
```
cd web_crawler
composer install
```
- If you want to download files, create new folder to store them. The default folder is "download"
```
mkdir download
```
## EXECUTE THE CODE
- Use the Live Server Extension of VSC
- Enable "Apache" and "mysqld" on XAMPP Control Panel
- Open the repo in VSC
- "GO LIVE" at the bottom right
- Open "test.php", right click then choose "PHP: Serve Project"
