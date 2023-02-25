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
2. Clone this repo to your local server
3. Import database voxteneotest.sql to your local server
4. After database imported, please navigate to /web/sites/default/settings.php and change database configuration
5. Clear cache by type "drush cr" on your console or use rebuild script (https://www.drupal.org/docs/user_guide/en/prevent-cache-clear.html)
6. The site should be working now