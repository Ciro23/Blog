# Blog
Complete working blog with signup, login, posting articles, comment and manage topics and users systems

# To make the website works
1) Copy every folders and files in the root of your server;
2) Import the database tables uploading the "phpmyadmin.sql" file on your server database.
3) The first admin must be promoted from the database, changing the role value to 2

# Website info
1) The database connection file directory is /includes/general/db_conn.php;
2) To change the website name, go to the directory /includes/general/common.php and change the value of the variable $siteName;
3) To change the website logo and the default image for articles, replace the "logo.jpg" and the "post_default.jpg" in /includes/general/. Both files must be jpg files
4) The footer initially contains nothing because is something highly subjective
