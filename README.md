# Vox Teneo Test

User login :
username : voxteneotest
password : voxteneotest1234567890

Login URL : [localurl]/user/login

Database name : voxteneotest.sql

Notes:

For "Event Description" field in event content type, I prefer to use (formatted, long, with summary) type because I think summary field is needed for the event card excerpt / mini description



Step by step:
1. Please setup local virtual host first to reduce link or image broken potential while testing
2. If you are not using virtual host, please change .htaccess file inside /web/ line 121 and then input your local link, /[localproject]/web , 
Example :  

RewriteBase /voxteneotest/web

3. Clone this repo to your local server
4. Import database voxteneotest.sql to your local server
5. After database imported, please navigate to /web/sites/default/settings.php and change database configuration
6. Clear cache by type "drush cr" on your console or use rebuild script (https://www.drupal.org/docs/user_guide/en/prevent-cache-clear.html)
7. The site should be working now