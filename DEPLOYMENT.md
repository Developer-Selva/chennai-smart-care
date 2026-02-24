# EC2 Deployment Guide — Chennai Smart Care
## Ubuntu 22.04 · t3.micro · 30GB SSD

---

## PART 1 — Connect to Your EC2

```bash
# From your local machine (replace with your key and IP)
chmod 400 ~/Downloads/your-key.pem
ssh -i ~/Downloads/your-key.pem ubuntu@YOUR_EC2_PUBLIC_IP
```

---

## PART 2 — Run Server Setup (one-time)

```bash
# Switch to root
sudo -i

# Download and run the setup script
curl -O https://raw.githubusercontent.com/YOUR_USERNAME/chennai-smart-care/main/server-setup.sh
# OR copy it manually, then:
bash server-setup.sh
```

This installs: **PHP 8.2, Composer, Node 20, MySQL 8, Nginx, Redis, Supervisor, Certbot, UFW**

Takes ~5–8 minutes. When done, view your DB credentials:
```bash
cat /root/.db_credentials
```

---

## PART 3 — Push Your Code to GitHub

On your **local machine**:
```bash
cd /path/to/chennai-smart-care

git init
git add .
git commit -m "Initial commit"

# Create a repo on github.com first, then:
git remote add origin https://github.com/YOUR_USERNAME/chennai-smart-care.git
git push -u origin main
```

**Important — add to `.gitignore`:**
```
/node_modules
/public/build
/.env
/vendor
/storage/logs/*.log
```

---

## PART 4 — Deploy the App

Back on the EC2 server:
```bash
# Update REPO_URL in deploy.sh first:
nano /root/deploy.sh
# Change: REPO_URL="https://github.com/YOUR_USERNAME/chennai-smart-care.git"

# First deploy:
bash deploy.sh --fresh

# All future deploys:
bash deploy.sh
```

---

## PART 5 — Configure .env

```bash
nano /var/www/smartcare/.env
```

Key settings to update:
```env
APP_NAME="Chennai Smart Care"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com          # or http://YOUR_EC2_IP

DB_DATABASE=smartcare
DB_USERNAME=smartcare_user
DB_PASSWORD=                            # from /root/.db_credentials

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail (use SES, Mailgun or SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your@mailgun.com
MAIL_PASSWORD=your-mailgun-password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Chennai Smart Care"

# SMS (Twilio or MSG91)
MSG91_API_KEY=your-msg91-key
MSG91_SENDER_ID=SMTCRE
```

After editing .env:
```bash
cd /var/www/smartcare
php artisan config:cache
```

---

## PART 6 — Create Admin User

```bash
cd /var/www/smartcare
php artisan tinker

# Inside tinker:
\App\Models\Admin::create([
    'name' => 'Super Admin',
    'email' => 'admin@chennaismartcare.com',
    'password' => bcrypt('your-strong-password'),
    'role' => 'super_admin',
]);
exit
```

---

## PART 7 — SSL Certificate (needs a domain)

Point your domain's A record → EC2 Public IP, then:

```bash
# Install SSL
certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renew is set up automatically. Test with:
certbot renew --dry-run
```

Update Nginx after SSL:
```bash
# Nginx will be auto-updated by certbot
# Verify config:
nginx -t && systemctl reload nginx
```

---

## PART 8 — EC2 Security Group (AWS Console)

In AWS Console → EC2 → Security Groups → Your instance's group → Inbound Rules:

| Type       | Protocol | Port | Source    |
|------------|----------|------|-----------|
| SSH        | TCP      | 22   | Your IP   |
| HTTP       | TCP      | 80   | 0.0.0.0/0 |
| HTTPS      | TCP      | 443  | 0.0.0.0/0 |

**Do NOT open port 3306 (MySQL) to the internet.**

---

## DAILY OPERATIONS

```bash
# Deploy latest code
bash /root/deploy.sh

# View app logs
tail -f /var/www/smartcare/storage/logs/laravel.log

# View Nginx logs
tail -f /var/log/nginx/smartcare_error.log

# View queue worker logs
tail -f /var/www/smartcare/storage/logs/worker.log

# Restart queue workers
supervisorctl restart smartcare-worker:*

# Clear all caches
cd /var/www/smartcare
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# MySQL access
mysql -u smartcare_user -p smartcare
# (password from /root/.db_credentials)

# Check all services
systemctl status nginx php8.2-fpm mysql redis-server supervisor
```

---

## MONITORING — Disk & Memory

```bash
# Disk usage
df -h

# Memory usage
free -h

# CPU & process monitor
htop

# Nginx + PHP processes
ps aux | grep -E 'nginx|php-fpm'
```

---

## TROUBLESHOOTING

**500 error:**
```bash
tail -50 /var/www/smartcare/storage/logs/laravel.log
# Check APP_DEBUG=true temporarily, then set back to false
```

**Permission denied:**
```bash
chown -R www-data:www-data /var/www/smartcare/storage
chmod -R 775 /var/www/smartcare/storage
```

**Queue jobs not running:**
```bash
supervisorctl status
supervisorctl restart smartcare-worker:*
```

**Nginx config test:**
```bash
nginx -t
```

**PHP-FPM not responding:**
```bash
systemctl restart php8.2-fpm
```
