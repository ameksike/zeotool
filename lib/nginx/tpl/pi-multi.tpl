# Ejemplo de configuración de proxy inverso nginx para mostrar archivos de sitio en producción - WordPress Multisitio con dominios
# Mapeo de blog_id a dominios en producción
# DEBE estar fuera del bloque server
map $blog_id $remote_host {
2    www.foo.com;
3    www.bar.net;
4    www.lorem.org;
5    www.ipsum.ble;
# ... and so on and so on
}

server {
server_name www.multisitio.local *.multisitio.local;
root /var/www/multisitio.com/htdocs;

# DNS externo: OpenDNS + Google Public DNS
resolver 208.67.222.222 208.67.220.220 8.8.8.8 8.8.4.4;

# Regex Vodoo
# Define la variable $blog_id, que hace la correspondencia con el dominio de producción
location ~* ^/wp-content/uploads/sites/((?
<blog_id>[0-9]+))\/(.*)$ {
    if ( !-e $request_filename ) {
    proxy_pass http://$remote_host$uri;
    }
    }

    # CONFIGURACIONES ESTÁNDAR
    # -- Ver http://codex.wordpress.org/Nginx
    # restricciones globales
    include global/restrictions.conf;
    # configuración para sitio único
    include global/wordpress.conf;
    }
