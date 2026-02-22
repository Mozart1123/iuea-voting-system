#!/bin/bash
# DEPLOYMENT SCRIPT FOR IUEA GUILDVOTE
# Run this after pulling the latest code

echo "🚀 IUEA GuildVote Deployment Script"
echo "===================================="

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Clear caches
echo -e "${YELLOW}[1/6]${NC} Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo -e "${GREEN}✓ Caches cleared${NC}"

# 2. Install dependencies
echo -e "${YELLOW}[2/6]${NC} Installing dependencies..."
composer install --no-dev --optimize-autoloader
echo -e "${GREEN}✓ Dependencies installed${NC}"

# 3. Run migrations
echo -e "${YELLOW}[3/6]${NC} Running database migrations..."
php artisan migrate --force
echo -e "${GREEN}✓ Migrations completed${NC}"

# 4. Seed database (optional)
echo -e "${YELLOW}[4/6]${NC} Seeding database..."
php artisan db:seed --force
echo -e "${GREEN}✓ Database seeded${NC}"

# 5. Create caches
echo -e "${YELLOW}[5/6]${NC} Creating application caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo -e "${GREEN}✓ Caches created${NC}"

# 6. Set permissions
echo -e "${YELLOW}[6/6]${NC} Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 777 storage/logs
echo -e "${GREEN}✓ Permissions set${NC}"

echo ""
echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
echo ""
echo "📋 Next steps:"
echo "1. Start queue worker: php artisan queue:work --daemon"
echo "2. Verify API: curl http://localhost:8000/api/categories"
echo "3. Check logs: tail -f storage/logs/laravel.log"
echo ""
echo "🎉 Your system is ready to use!"
