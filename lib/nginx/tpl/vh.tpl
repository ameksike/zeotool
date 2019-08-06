server {
listen {$port};
server_name {$name};
index index.php index.html index.htm;
root {$www};

location ~* \.(jpg|jpeg|png|ico|gif|txt|js|css|swf|zip|rar|webm|woff|json|xml|html)$ {
access_log        off;
expires           max;
}

location ~ \.php$ {
try_files \$uri = 404;
fastcgi_pass unix:/var/run/php5-fpm.sock;
fastcgi_index index.php;

fastcgi_connect_timeout 60;
fastcgi_send_timeout 180;
fastcgi_read_timeout 180;
fastcgi_buffer_size 128k;
fastcgi_buffers 4 256k;
fastcgi_busy_buffers_size 256k;
fastcgi_temp_file_write_size 256k;
fastcgi_intercept_errors on;
include /etc/nginx/fastcgi_params;
}
}
