# cornerstonejeffcity.org

PHP has a default upload max size of 8 MB. To support larger files sizes in this shared environment the you can add/edit the /home/{user}/public_html/.user.ini file.

```
[PHP]
allow_url_fopen = Off
allow_url_include = Off
asp_tags = Off
display_errors = Off
enable_dl = Off
file_uploads = On
max_execution_time = 1200
max_input_time = 900
max_input_vars = 1000
memory_limit = 128M
session.save_path = ""
upload_max_filesize = 56M
post_max_size = 56M
```

For documentation see [php.net|.user.ini files](http://php.net/manual/en/configuration.file.per-user.php)