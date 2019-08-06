upstream {$dem}pi{
least_conn;
{$server}
}

server {
listen {$port};
server_name {$name};

location ~* \.(jpg|jpeg|png|ico|gif|txt|js|css|swf|zip|rar|webm|woff|json|xml|html)$ {
root {$www};
access_log        off;
expires           max;
}

location / {
fastcgi_connect_timeout 60;
fastcgi_send_timeout 180;
fastcgi_read_timeout 180;
fastcgi_buffer_size 128k;
fastcgi_buffers 4 256k;
fastcgi_busy_buffers_size 256k;
fastcgi_temp_file_write_size 256k;
fastcgi_intercept_errors on;

proxy_pass http://{$dem}pi;
}
}
