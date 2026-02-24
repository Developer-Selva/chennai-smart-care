#!/bin/bash
# =============================================================
# Chennai Smart Care — EC2 Ubuntu 22.04 Server Setup
# Run as: bash server-setup.sh
# =============================================================

set -e  # Exit on any error
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; BLUE='\033[0;34m'; NC='\033[0m'
log()  { echo -e "${GREEN}[✓] $1${NC}"; }
warn() { echo -e "${YELLOW}[!] $1${NC}"; }
step() { echo -e "\n${BLUE}━━━ $1 ━━━${NC}"; }

# =============================================================
# STEP 1 — SWAP (critical for t3.micro — only 1GB RAM)
# =============================================================
step "Creating 2GB Swap Space"
if [ ! -f /swapfile ]; then
  fallocate -l 2G /swapfile
  chmod 600 /swapfile
  mkswap /swapfile
  swapon /swapfile
  echo '/swapfile none swap sw 0 0' >> /etc/fstab
  echo 'vm.swappiness=10' >> /etc/sysctl.conf
  sysctl -p
  log "Swap created (2GB)"
else
  warn "Swap already exists, skipping"
fi

# =============================================================
# STEP 2 — SYSTEM UPDATE
# =============================================================
step "Updating System Packages"
apt update -y && apt upgrade -y
apt install -y curl wget git unzip zip nano ufw \
  software-properties-common apt-transport-https ca-certificates
log "System updated"

# =============================================================
# STEP 3 — PHP 8.2
# =============================================================
step "Installing PHP 8.2"
add-apt-repository ppa:ondrej/php -y
apt update -y
apt install -y \
  php8.2 php8.2-fpm php8.2-cli php8.2-common \
  php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl \
  php8.2-zip php8.2-bcmath php8.2-gd php8.2-intl \
  php8.2-redis php8.2-opcache php8.2-tokenizer

# PHP-FPM performance settings for t3.micro
sed -i 's/^pm = .*/pm = ondemand/' /etc/php/8.2/fpm/pool.d/www.conf
sed -i 's/^pm.max_children = .*/pm.max_children = 10/' /etc/php/8.2/fpm/pool.d/www.conf
sed -i 's/^;pm.process_idle_timeout = .*/pm.process_idle_timeout = 10s/' /etc/php/8.2/fpm/pool.d/www.conf

# php.ini tweaks
PHP_INI=/etc/php/8.2/fpm/php.ini
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 20M/' $PHP_INI
sed -i 's/post_max_size = .*/post_max_size = 20M/' $PHP_INI
sed -i 's/memory_limit = .*/memory_limit = 256M/' $PHP_INI
sed -i 's/max_execution_time = .*/max_execution_time = 60/' $PHP_INI
sed -i 's/;opcache.enable=.*/opcache.enable=1/' $PHP_INI
sed -i 's/;opcache.memory_consumption=.*/opcache.memory_consumption=128/' $PHP_INI

systemctl enable php8.2-fpm && systemctl restart php8.2-fpm
log "PHP 8.2 installed → $(php -v | head -1)"

# =============================================================
# STEP 4 — COMPOSER
# =============================================================
step "Installing Composer"
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
log "Composer installed → $(composer --version)"

# =============================================================
# STEP 5 — NODE.JS 20 LTS
# =============================================================
step "Installing Node.js 20 LTS"
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
npm install -g npm@latest
log "Node installed → $(node -v) | npm → $(npm -v)"

# =============================================================
# STEP 6 — MYSQL 8
# =============================================================
step "Installing MySQL 8"
apt install -y mysql-server

# Secure MySQL and create app database
DB_PASSWORD=$(openssl rand -base64 16 | tr -d '/+=')
DB_NAME="smartcare"
DB_USER="smartcare_user"

mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '${DB_PASSWORD}_root';"
mysql -u root -p"${DB_PASSWORD}_root" << MYSQL_SCRIPT
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
MYSQL_SCRIPT

# Save credentials to a safe file
cat > /root/.db_credentials << CREDS
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD=${DB_PASSWORD}
ROOT_PASSWORD=${DB_PASSWORD}_root
CREDS
chmod 600 /root/.db_credentials

systemctl enable mysql
log "MySQL installed — credentials saved to /root/.db_credentials"
warn "IMPORTANT: Run 'cat /root/.db_credentials' to view your DB passwords"

# =============================================================
# STEP 7 — NGINX
# =============================================================
step "Installing Nginx"
apt install -y nginx
systemctl enable nginx
log "Nginx installed → $(nginx -v 2>&1)"

# =============================================================
# STEP 8 — NGINX SITE CONFIG
# =============================================================
step "Configuring Nginx for Laravel"
APP_DIR="/var/www/smartcare"
mkdir -p $APP_DIR

cat > /etc/nginx/sites-available/smartcare << 'NGINX'
server {
    listen 80;
    listen [::]:80;
    server_name _;   # Replace with your domain or EC2 public IP

    root /var/www/smartcare/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    # Gzip compression
    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml text/javascript;
    gzip_min_length 1000;

    # Static assets — long cache
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot|webp)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }

    # Laravel front controller
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_read_timeout 60;
    }

    # Block hidden files
    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Increase upload size
    client_max_body_size 20M;

    # Logs
    access_log /var/log/nginx/smartcare_access.log;
    error_log  /var/log/nginx/smartcare_error.log;
}
NGINX

# Enable site
ln -sf /etc/nginx/sites-available/smartcare /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

nginx -t && systemctl reload nginx
log "Nginx configured"

# =============================================================
# STEP 9 — REDIS (for cache/sessions/queue)
# =============================================================
step "Installing Redis"
apt install -y redis-server
sed -i 's/^supervised no/supervised systemd/' /etc/redis/redis.conf
sed -i 's/^# maxmemory <bytes>/maxmemory 128mb/' /etc/redis/redis.conf
sed -i 's/^# maxmemory-policy noeviction/maxmemory-policy allkeys-lru/' /etc/redis/redis.conf
systemctl enable redis-server && systemctl restart redis-server
log "Redis installed and running"

# =============================================================
# STEP 10 — SUPERVISOR (for Laravel queues)
# =============================================================
step "Installing Supervisor"
apt install -y supervisor

cat > /etc/supervisor/conf.d/smartcare-worker.conf << 'SUPERVISOR'
[program:smartcare-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/smartcare/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/smartcare/storage/logs/worker.log
stopwaitsecs=3600
SUPERVISOR

systemctl enable supervisor && systemctl restart supervisor
log "Supervisor installed"

# =============================================================
# STEP 11 — FIREWALL
# =============================================================
step "Configuring UFW Firewall"
ufw --force reset
ufw default deny incoming
ufw default allow outgoing
ufw allow ssh
ufw allow 'Nginx Full'   # ports 80 + 443
ufw --force enable
log "Firewall configured (SSH + HTTP/HTTPS allowed)"

# =============================================================
# STEP 12 — APP DIRECTORY PERMISSIONS
# =============================================================
step "Setting Up App Directory"
chown -R www-data:www-data $APP_DIR
chmod -R 755 $APP_DIR

# Create storage dirs in advance
mkdir -p $APP_DIR/storage/{app/public,framework/{cache,sessions,views},logs}
mkdir -p $APP_DIR/bootstrap/cache
chown -R www-data:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

log "Permissions set"

# =============================================================
# STEP 13 — CERTBOT (SSL) — optional, needs a domain
# =============================================================
step "Installing Certbot (SSL)"
apt install -y certbot python3-certbot-nginx
log "Certbot installed — run 'certbot --nginx -d yourdomain.com' after DNS is pointed"

# =============================================================
# STEP 14 — CRON FOR LARAVEL SCHEDULER
# =============================================================
step "Adding Laravel Scheduler to Cron"
(crontab -l 2>/dev/null; echo "* * * * * www-data php /var/www/smartcare/artisan schedule:run >> /dev/null 2>&1") | crontab -
log "Laravel scheduler cron added"

# =============================================================
# DONE
# =============================================================
echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║       SERVER SETUP COMPLETE ✓                        ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}Installed:${NC}"
echo "  PHP:      $(php -v | head -1 | awk '{print $1, $2}')"
echo "  Composer: $(composer --version | awk '{print $1, $2, $3}')"
echo "  Node:     $(node -v)"
echo "  MySQL:    $(mysql --version | awk '{print $1, $2, $3}')"
echo "  Nginx:    $(nginx -v 2>&1)"
echo "  Redis:    $(redis-cli --version)"
echo ""
echo -e "${YELLOW}DB Credentials:${NC} cat /root/.db_credentials"
echo ""
echo -e "${BLUE}━━━ NEXT STEPS ━━━${NC}"
echo "  1. Upload your app:  bash deploy.sh   (see deploy.sh)"
echo "  2. Point your domain to this EC2 IP"
echo "  3. Run SSL:  certbot --nginx -d yourdomain.com"
echo ""
