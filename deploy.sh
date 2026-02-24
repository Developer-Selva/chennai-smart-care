#!/bin/bash
# =============================================================
# Chennai Smart Care — Deploy Script
# Run ON THE SERVER as: bash deploy.sh
# First time: bash deploy.sh --fresh
# Subsequent deploys: bash deploy.sh
# =============================================================

set -e
RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; BLUE='\033[0;34m'; NC='\033[0m'
log()  { echo -e "${GREEN}[✓] $1${NC}"; }
warn() { echo -e "${YELLOW}[!] $1${NC}"; }
step() { echo -e "\n${BLUE}━━━ $1 ━━━${NC}"; }

APP_DIR="/var/www/smartcare"
REPO_URL="https://github.com/YOUR_USERNAME/chennai-smart-care.git"  # ← Update this
BRANCH="main"
FRESH=${1:-""}

# =============================================================
# CLONE OR PULL
# =============================================================
step "Getting Latest Code"
if [ "$FRESH" = "--fresh" ] || [ ! -d "$APP_DIR/.git" ]; then
  warn "Fresh deploy — cloning repository"
  rm -rf $APP_DIR
  git clone -b $BRANCH $REPO_URL $APP_DIR
  log "Repository cloned"
else
  cd $APP_DIR
  git fetch origin
  git reset --hard origin/$BRANCH
  log "Code updated to latest"
fi

cd $APP_DIR

# =============================================================
# .ENV SETUP (first time only)
# =============================================================
step "Environment Configuration"
if [ ! -f "$APP_DIR/.env" ]; then
  cp .env.example .env

  # Read DB creds saved by server-setup.sh
  if [ -f /root/.db_credentials ]; then
    source /root/.db_credentials
    sed -i "s/^DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
    sed -i "s/^DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
    sed -i "s/^DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env
  fi

  sed -i "s/^APP_ENV=.*/APP_ENV=production/" .env
  sed -i "s/^APP_DEBUG=.*/APP_DEBUG=false/" .env
  sed -i "s/^APP_URL=.*/APP_URL=http:\/\/$(curl -s ifconfig.me)/" .env
  sed -i "s/^CACHE_DRIVER=.*/CACHE_DRIVER=redis/" .env
  sed -i "s/^SESSION_DRIVER=.*/SESSION_DRIVER=redis/" .env
  sed -i "s/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/" .env

  php artisan key:generate --force
  warn ".env created — edit it to add MAIL, SMS settings: nano $APP_DIR/.env"
else
  log ".env already exists — skipping (run 'nano $APP_DIR/.env' to edit)"
fi

# =============================================================
# COMPOSER
# =============================================================
step "Installing PHP Dependencies"
composer install --no-dev --optimize-autoloader --no-interaction
log "Composer done"

# =============================================================
# NPM BUILD
# =============================================================
step "Building Frontend Assets"
npm ci --prefer-offline
npm run build
log "Vite build complete"

# =============================================================
# STORAGE LINK
# =============================================================
step "Linking Storage"
php artisan storage:link --force 2>/dev/null || true
log "Storage linked"

# =============================================================
# DATABASE
# =============================================================
step "Running Migrations"
if [ "$FRESH" = "--fresh" ]; then
  warn "Fresh deploy — running migrations"
  php artisan migrate --force
else
  php artisan migrate --force
fi
log "Migrations done"

# =============================================================
# CACHE & OPTIMIZE
# =============================================================
step "Optimizing Application"
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan icons:cache 2>/dev/null || true
log "Application optimized"

# =============================================================
# PERMISSIONS
# =============================================================
step "Setting Permissions"
chown -R www-data:www-data $APP_DIR
chmod -R 755 $APP_DIR
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache
log "Permissions set"

# =============================================================
# RESTART SERVICES
# =============================================================
step "Restarting Services"
systemctl reload php8.2-fpm
systemctl reload nginx
supervisorctl restart smartcare-worker:* 2>/dev/null || true
log "Services restarted"

# =============================================================
# DONE
# =============================================================
PUBLIC_IP=$(curl -s ifconfig.me)
echo ""
echo -e "${GREEN}╔══════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║   DEPLOY COMPLETE ✓                                  ║${NC}"
echo -e "${GREEN}╚══════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "  App URL: ${BLUE}http://${PUBLIC_IP}${NC}"
echo ""
echo -e "${YELLOW}Post-deploy checklist:${NC}"
echo "  □ Update APP_URL in .env if you have a domain"
echo "  □ Configure mail: MAIL_HOST, MAIL_USERNAME etc."
echo "  □ Set up SSL: certbot --nginx -d yourdomain.com"
echo "  □ Seed initial data: php artisan db:seed"
echo "  □ Create admin user: php artisan tinker"
echo ""
