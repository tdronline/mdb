# mdb
Web based local movie management solution

**Configuration**

* Eidit DB and File locations from `includes/config.php`

* Add your Movie Drive to the apache configuration

	`Alias /mdb/movies "A:/Drive/Folder"`

 	`<Directory "A:/Drive/Folder">`
    `Options +Indexes +FollowSymLinks +MultiViews`
	`AllowOverride All`
	`Require local`
	`Require all granted`
	`</Directory>`

* 
