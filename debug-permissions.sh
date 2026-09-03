#!/bin/bash

echo "=== COMPREHENSIVE PERMISSION DEBUGGING ==="
echo ""

WWW_DIR="/www/wwwroot/kemenagtanahdatar.id"

# 1. Check ownership
echo "1. Checking ownership..."
echo "Storage directory owner:"
ls -la "$WWW_DIR/storage/" | head -5
echo ""
echo "Storage/app/public owner:"
ls -la "$WWW_DIR/storage/app/public/" | head -5
echo ""

# 2. Check if www-data can write
echo "2. Testing write as www-data..."
sudo -u www-data touch "$WWW_DIR/storage/app/public/test_write.txt" 2>&1
if [ $? -eq 0 ]; then
    echo "✅ www-data can write to storage/app/public"
    sudo -u www-data rm "$WWW_DIR/storage/app/public/test_write.txt"
else
    echo "❌ www-data CANNOT write to storage/app/public"
fi
echo ""

# 3. Check PHP-FPM user
echo "3. Checking PHP-FPM process user..."
PHP_USER=$(ps aux | grep php-fpm | grep -v grep | awk '{print $1}' | head -1)
if [ -n "$PHP_USER" ]; then
    echo "PHP-FPM running as: $PHP_USER"
else
    echo "PHP-FPM not found or not running"
fi
echo ""

# 4. Check SELinux
echo "4. Checking SELinux..."
if command -v getenforce &> /dev/null; then
    SELINUX_STATUS=$(getenforce)
    echo "SELinux status: $SELINUX_STATUS"
    if [ "$SELINUX_STATUS" = "Enforcing" ]; then
        echo "⚠️  SELinux is enforcing - may block file writes"
        echo "   Check context: ls -Z $WWW_DIR/storage/app/public/"
    fi
else
    echo "SELinux not installed"
fi
echo ""

# 5. Check AppArmor
echo "5. Checking AppArmor..."
if command -v aa-status &> /dev/null; then
    echo "AppArmor status:"
    sudo aa-status 2>&1 | head -5
else
    echo "AppArmor not installed"
fi
echo ""

# 6. Check filesystem mount options
echo "6. Checking filesystem mount options..."
df -h "$WWW_DIR/storage/" | tail -1
mount | grep "$(df -h "$WWW_DIR/storage/" | tail -1 | awk '{print $1}')"
echo ""

# 7. Check open_basedir
echo "7. Checking PHP open_basedir..."
php -i 2>/dev/null | grep "open_basedir" || echo "open_basedir not set"
echo ""

# 8. Check disk space
echo "8. Checking disk space..."
df -h "$WWW_DIR/storage/"
echo ""

# 9. Test actual write in PHP
echo "9. Testing write in PHP..."
php -r "
\$path = '$WWW_DIR/storage/app/public/test_php_write.txt';
if (file_put_contents(\$path, 'test')) {
    echo '✅ PHP can write to storage/app/public' . PHP_EOL;
    unlink(\$path);
} else {
    echo '❌ PHP CANNOT write to storage/app/public' . PHP_EOL;
    echo 'Error: ' . error_get_last()['message'] . PHP_EOL;
}
" 2>&1
echo ""

echo "=== DEBUGGING COMPLETE ==="
