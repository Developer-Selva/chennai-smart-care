#!/bin/bash
# =============================================================
# Chennai Smart Care — Queue Health Check
# Run on server: bash queue-check.sh
# =============================================================

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m'

APP_DIR="/var/www/smartcare"

ok()   { echo -e "  ${GREEN}✓${NC}  $1"; }
fail() { echo -e "  ${RED}✗${NC}  $1"; }
warn() { echo -e "  ${YELLOW}⚠${NC}  $1"; }
info() { echo -e "  ${CYAN}→${NC}  $1"; }

echo ""
echo -e "${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BOLD}  Chennai Smart Care — Queue Health Check${NC}"
echo -e "${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""

cd $APP_DIR

# ──────────────────────────────────────────────────────────────
# 1. SUPERVISOR PROCESS STATUS
# ──────────────────────────────────────────────────────────────
echo -e "${BOLD}[1] Supervisor Worker Status${NC}"
if command -v supervisorctl &>/dev/null; then
    STATUS=$(supervisorctl status smartcare-worker:* 2>&1)
    echo "$STATUS" | while IFS= read -r line; do
        if echo "$line" | grep -q "RUNNING"; then
            ok "$line"
        elif echo "$line" | grep -q "STOPPED\|FATAL\|EXITED\|BACKOFF"; then
            fail "$line"
        else
            warn "$line"
        fi
    done

    # Count running workers
    RUNNING=$(echo "$STATUS" | grep -c "RUNNING" || true)
    TOTAL=$(echo "$STATUS" | grep -c "smartcare" || true)
    echo ""
    info "Workers: ${RUNNING}/${TOTAL} running"
else
    fail "supervisorctl not found"
fi
echo ""

# ──────────────────────────────────────────────────────────────
# 2. QUEUE DRIVER & REDIS CONNECTION
# ──────────────────────────────────────────────────────────────
echo -e "${BOLD}[2] Queue Configuration${NC}"

QUEUE_CONN=$(php artisan tinker --execute="echo config('queue.default');" 2>/dev/null | tail -1)
info "QUEUE_CONNECTION = ${BOLD}${QUEUE_CONN}${NC}"

if [ "$QUEUE_CONN" = "redis" ]; then
    ok "Using Redis (recommended for production)"
    # Test Redis connection
    REDIS_TEST=$(php artisan tinker --execute="
        try { \Illuminate\Support\Facades\Redis::ping(); echo 'PONG'; }
        catch(\Exception \$e) { echo 'ERROR: '.\$e->getMessage(); }
    " 2>/dev/null | tail -1)

    if echo "$REDIS_TEST" | grep -q "PONG"; then
        ok "Redis connection: PONG received"
    else
        fail "Redis connection failed: $REDIS_TEST"
    fi
elif [ "$QUEUE_CONN" = "sync" ]; then
    warn "QUEUE_CONNECTION=sync — jobs run synchronously, no worker needed"
    warn "For production, set QUEUE_CONNECTION=redis in .env"
elif [ "$QUEUE_CONN" = "database" ]; then
    warn "QUEUE_CONNECTION=database — works but Redis is faster for production"
else
    warn "QUEUE_CONNECTION=$QUEUE_CONN"
fi
echo ""

# ──────────────────────────────────────────────────────────────
# 3. PENDING / FAILED JOBS
# ──────────────────────────────────────────────────────────────
echo -e "${BOLD}[3] Job Queue Status${NC}"

if [ "$QUEUE_CONN" = "redis" ]; then
    # Redis queue sizes
    DEFAULT_SIZE=$(php artisan tinker --execute="
        try {
            \$r = \Illuminate\Support\Facades\Redis::connection();
            echo \$r->llen('queues:default');
        } catch(\Exception \$e) { echo '?'; }
    " 2>/dev/null | tail -1)

    DELAYED=$(php artisan tinker --execute="
        try {
            \$r = \Illuminate\Support\Facades\Redis::connection();
            echo \$r->zcard('queues:default:delayed');
        } catch(\Exception \$e) { echo '?'; }
    " 2>/dev/null | tail -1)

    RESERVED=$(php artisan tinker --execute="
        try {
            \$r = \Illuminate\Support\Facades\Redis::connection();
            echo \$r->zcard('queues:default:reserved');
        } catch(\Exception \$e) { echo '?'; }
    " 2>/dev/null | tail -1)

    if [ "$DEFAULT_SIZE" = "0" ]; then
        ok "Pending jobs (default queue): 0"
    else
        warn "Pending jobs (default queue): ${DEFAULT_SIZE}"
    fi
    info "Delayed jobs:  ${DELAYED}"
    info "Reserved jobs: ${RESERVED}"

elif [ "$QUEUE_CONN" = "database" ]; then
    PENDING=$(php artisan tinker --execute="echo \App\Models\Job::where('queue','default')->count();" 2>/dev/null | tail -1)
    info "Pending jobs in DB: ${PENDING}"
fi

# Failed jobs (works for both redis and database)
FAILED=$(php artisan queue:failed 2>/dev/null | grep -c "App\\" || true)
if [ "$FAILED" = "0" ]; then
    ok "Failed jobs: 0"
else
    fail "Failed jobs: ${FAILED} — run: php artisan queue:failed"
fi
echo ""

# ──────────────────────────────────────────────────────────────
# 4. SEND A TEST JOB & VERIFY IT PROCESSES
# ──────────────────────────────────────────────────────────────
echo -e "${BOLD}[4] Live Job Processing Test${NC}"
info "Dispatching a test job to the queue..."

TEST_RESULT=$(php artisan tinker --execute="
    \$key = 'queue_test_' . time();
    cache()->forget(\$key);
    \App\Jobs\QueueHealthCheck::dispatch(\$key);
    sleep(3);
    echo cache()->get(\$key, 'not_processed');
" 2>/dev/null | tail -1)

if [ "$TEST_RESULT" = "processed" ]; then
    ok "Test job dispatched AND processed successfully ✓"
else
    if [ "$QUEUE_CONN" = "sync" ]; then
        ok "sync driver — job ran inline (not queued)"
    else
        fail "Test job was NOT processed within 3 seconds"
        warn "Worker may be stopped. Run: supervisorctl restart smartcare-worker:*"
    fi
fi
echo ""

# ──────────────────────────────────────────────────────────────
# 5. WORKER PROCESS (ps check)
# ──────────────────────────────────────────────────────────────
echo -e "${BOLD}[5] Worker Processes (OS level)${NC}"
WORKER_PROCS=$(ps aux | grep "queue:work" | grep -v grep || true)
if [ -n "$WORKER_PROCS" ]; then
    ok "queue:work processes found:"
    echo "$WORKER_PROCS" | while IFS= read -r line; do
        PID=$(echo $line | awk '{print $2}')
        CPU=$(echo $line | awk '{print $3}')
        MEM=$(echo $line | awk '{print $4}')
        UPTIME=$(echo $line | awk '{print $11,$12}')
        info "  PID ${PID} | CPU ${CPU}% | MEM ${MEM}% | ${UPTIME}"
    done
else
    fail "No queue:work processes found in OS process list"
fi
echo ""

# ──────────────────────────────────────────────────────────────
# 6. SUPERVISOR CONFIG CHECK
# ──────────────────────────────────────────────────────────────
echo -e "${BOLD}[6] Supervisor Config File${NC}"
CONF_FILE="/etc/supervisor/conf.d/smartcare.conf"
if [ -f "$CONF_FILE" ]; then
    ok "Config found: $CONF_FILE"
    echo ""
    cat "$CONF_FILE" | while IFS= read -r line; do
        info "  $line"
    done
else
    fail "Config not found at $CONF_FILE"
    warn "Creating supervisor config..."

    cat > /etc/supervisor/conf.d/smartcare.conf << 'SUPCONF'
[program:smartcare-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/smartcare/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600 --timeout=90
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/smartcare/storage/logs/worker.log
stdout_logfile_maxbytes=10MB
stdout_logfile_backups=5
stopwaitsecs=3600
SUPCONF

    supervisorctl reread
    supervisorctl update
    supervisorctl start smartcare-worker:*
    ok "Supervisor config created and workers started!"
fi
echo ""

# ──────────────────────────────────────────────────────────────
# 7. WORKER LOG (last 20 lines)
# ──────────────────────────────────────────────────────────────
echo -e "${BOLD}[7] Worker Log (last 20 lines)${NC}"
WORKER_LOG="$APP_DIR/storage/logs/worker.log"
LARAVEL_LOG="$APP_DIR/storage/logs/laravel.log"

if [ -f "$WORKER_LOG" ]; then
    ok "Worker log: $WORKER_LOG"
    echo ""
    tail -20 "$WORKER_LOG" | while IFS= read -r line; do
        if echo "$line" | grep -qiE "error|fail|exception"; then
            echo -e "  ${RED}$line${NC}"
        elif echo "$line" | grep -qiE "done|processed|success"; then
            echo -e "  ${GREEN}$line${NC}"
        else
            echo -e "  ${CYAN}$line${NC}"
        fi
    done
else
    warn "Worker log not found at $WORKER_LOG"
    info "Checking laravel.log for queue activity..."
    if [ -f "$LARAVEL_LOG" ]; then
        grep -i "queue\|job\|whatsapp\|warranty\|invoice" "$LARAVEL_LOG" 2>/dev/null | tail -10 | while IFS= read -r line; do
            echo -e "  ${CYAN}$line${NC}"
        done
    fi
fi
echo ""

# ──────────────────────────────────────────────────────────────
# 8. APP-SPECIFIC JOB CLASSES CHECK
# ──────────────────────────────────────────────────────────────
echo -e "${BOLD}[8] Registered Listeners & Jobs${NC}"
php artisan event:list 2>/dev/null | grep -E "BookingCreated|BookingConfirmed|SendWhatsApp|SendBooking" | while IFS= read -r line; do
    ok "$line"
done

php artisan queue:monitor default 2>/dev/null | head -5 || true
echo ""

# ──────────────────────────────────────────────────────────────
# SUMMARY
# ──────────────────────────────────────────────────────────────
echo -e "${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo -e "${BOLD}  Quick Fix Commands${NC}"
echo -e "${BOLD}${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
echo ""
echo -e "  ${YELLOW}Restart workers:${NC}"
echo -e "    supervisorctl restart smartcare-worker:*"
echo ""
echo -e "  ${YELLOW}View failed jobs:${NC}"
echo -e "    php artisan queue:failed"
echo ""
echo -e "  ${YELLOW}Retry all failed jobs:${NC}"
echo -e "    php artisan queue:retry all"
echo ""
echo -e "  ${YELLOW}Flush failed jobs:${NC}"
echo -e "    php artisan queue:flush"
echo ""
echo -e "  ${YELLOW}Watch worker live:${NC}"
echo -e "    tail -f $APP_DIR/storage/logs/worker.log"
echo ""
echo -e "  ${YELLOW}Run worker manually (debug):${NC}"
echo -e "    php artisan queue:work --verbose --tries=1"
echo ""
