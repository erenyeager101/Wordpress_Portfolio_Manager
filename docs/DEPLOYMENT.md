# Deployment Guide - Smart Portfolio WordPress Theme

## 📋 Pre-Deployment Checklist

- [ ] All theme files compiled and tested locally
- [ ] Plugin tested and working
- [ ] Database backup created
- [ ] Production server credentials ready
- [ ] Domain name configured
- [ ] SSL certificate installed

## 🚀 Deployment Methods

### Method 1: Using LocalWP (Recommended for Windows)

1. **Install LocalWP**
   ```
   Download from: https://localwp.com/
   ```

2. **Create New Site**
   - Open LocalWP
   - Click "Create a new site"
   - Choose site name: "Smart Portfolio"
   - Select "Preferred" environment
   - Set up WordPress admin credentials

3. **Install Theme & Plugin**
   - Right-click site → "Open site shell"
   - Navigate to wp-content:
     ```bash
     cd app/public/wp-content
     ```
   - Copy theme:
     ```bash
     cp -r /path/to/themes/smart-portfolio themes/
     ```
   - Copy plugin:
     ```bash
     cp -r /path/to/plugins/lead-manager plugins/
     ```

4. **Activate**
   - Go to site admin (click "WP Admin" in LocalWP)
   - Activate theme and plugin

### Method 2: Manual XAMPP Installation

1. **Install XAMPP**
   ```
   Download from: https://www.apachefriends.org/
   ```

2. **Download WordPress**
   ```
   Download from: https://wordpress.org/download/
   Extract to: C:\xampp\htdocs\wordpress
   ```

3. **Create Database**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Create new database: `smart_portfolio`
   - Collation: `utf8mb4_unicode_ci`

4. **Configure WordPress**
   - Copy `wp-config-sample.php` to `wp-config.php`
   - Edit database credentials:
     ```php
     define('DB_NAME', 'smart_portfolio');
     define('DB_USER', 'root');
     define('DB_PASSWORD', '');
     define('DB_HOST', 'localhost');
     ```

5. **Install Theme & Plugin**
   - Copy theme to: `C:\xampp\htdocs\wordpress\wp-content\themes\`
   - Copy plugin to: `C:\xampp\htdocs\wordpress\wp-content\plugins\`

6. **Complete Installation**
   - Visit: http://localhost/wordpress
   - Follow WordPress installation wizard
   - Activate theme and plugin

### Method 3: Production Server Deployment

#### Step 1: Prepare Files

1. **Build Production Assets**
   ```bash
   cd wp-content/themes/smart-portfolio
   npm run build
   ```

2. **Create Deployment Package**
   ```bash
   # Create a zip file with theme and plugin
   zip -r smart-portfolio-deploy.zip wp-content/
   ```

#### Step 2: Server Setup

1. **Upload WordPress Core**
   - Download latest WordPress
   - Upload via FTP/SFTP to server
   - Extract files

2. **Create Database**
   ```sql
   CREATE DATABASE smart_portfolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'wp_user'@'localhost' IDENTIFIED BY 'strong_password_here';
   GRANT ALL PRIVILEGES ON smart_portfolio.* TO 'wp_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. **Configure wp-config.php**
   ```php
   define('DB_NAME', 'smart_portfolio');
   define('DB_USER', 'wp_user');
   define('DB_PASSWORD', 'strong_password_here');
   define('DB_HOST', 'localhost');
   
   // Security keys - generate at https://api.wordpress.org/secret-key/1.1/salt/
   define('AUTH_KEY',         'put your unique phrase here');
   define('SECURE_AUTH_KEY',  'put your unique phrase here');
   // ... etc
   
   // Production settings
   define('WP_DEBUG', false);
   define('WP_DEBUG_LOG', false);
   define('WP_DEBUG_DISPLAY', false);
   define('DISALLOW_FILE_EDIT', true);
   ```

4. **Upload Theme & Plugin**
   ```bash
   # Via SFTP
   scp -r wp-content/themes/smart-portfolio user@server:/path/to/wordpress/wp-content/themes/
   scp -r wp-content/plugins/lead-manager user@server:/path/to/wordpress/wp-content/plugins/
   ```

5. **Set Permissions**
   ```bash
   # SSH into server
   ssh user@server
   
   # Set correct permissions
   cd /path/to/wordpress
   find . -type d -exec chmod 755 {} \;
   find . -type f -exec chmod 644 {} \;
   chmod 600 wp-config.php
   ```

#### Step 3: Complete WordPress Installation

1. Visit your domain: `https://yourdomain.com`
2. Complete WordPress installation wizard
3. Log in to admin panel
4. Go to **Appearance → Themes** → Activate **Smart Portfolio**
5. Go to **Plugins** → Activate **Lead Manager**

#### Step 4: Configure Site

1. **Create Pages**
   - Home (set as front page)
   - Projects (will show project archive)
   - Blog
   - Contact

2. **Set Permalinks**
   - Go to **Settings → Permalinks**
   - Choose "Post name"
   - Save changes

3. **Configure Menus**
   - Go to **Appearance → Menus**
   - Create "Primary Menu"
   - Add pages: Home, Projects, Blog, Contact
   - Assign to "Primary Menu" location

4. **Add Sample Content**
   - Create a few projects
   - Add featured images
   - Fill in project metadata
   - Publish some blog posts

## 🔒 Security Hardening

1. **Update Security Keys**
   ```bash
   # Generate new keys
   curl https://api.wordpress.org/secret-key/1.1/salt/
   # Add to wp-config.php
   ```

2. **Disable File Editing**
   ```php
   // In wp-config.php
   define('DISALLOW_FILE_EDIT', true);
   ```

3. **Limit Login Attempts**
   - Install "Limit Login Attempts Reloaded" plugin

4. **Enable SSL**
   ```php
   // In wp-config.php
   define('FORCE_SSL_ADMIN', true);
   ```

5. **Hide WordPress Version**
   ```php
   // In functions.php
   remove_action('wp_head', 'wp_generator');
   ```

## ⚡ Performance Optimization

1. **Install Caching Plugin**
   - WP Rocket (premium) or W3 Total Cache (free)

2. **Image Optimization**
   - Install "Smush" or "EWWW Image Optimizer"

3. **Enable Gzip Compression**
   ```apache
   # In .htaccess
   <IfModule mod_deflate.c>
     AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
   </IfModule>
   ```

4. **Browser Caching**
   ```apache
   # In .htaccess
   <IfModule mod_expires.c>
     ExpiresActive On
     ExpiresByType image/jpg "access plus 1 year"
     ExpiresByType image/jpeg "access plus 1 year"
     ExpiresByType image/gif "access plus 1 year"
     ExpiresByType image/png "access plus 1 year"
     ExpiresByType text/css "access plus 1 month"
     ExpiresByType application/javascript "access plus 1 month"
   </IfModule>
   ```

5. **CDN Setup** (Optional)
   - Cloudflare (free tier available)
   - Configure DNS to point through Cloudflare

## 🔄 Database Migration

### Export from Local

```bash
# Using WP-CLI
wp db export backup.sql

# Or using phpMyAdmin
# Export → SQL format → Save
```

### Import to Production

```bash
# Upload backup.sql to server
scp backup.sql user@server:/tmp/

# SSH into server
ssh user@server

# Import database
mysql -u wp_user -p smart_portfolio < /tmp/backup.sql

# Update URLs
wp search-replace 'http://localhost' 'https://yourdomain.com' --all-tables
```

## 📊 Post-Deployment Testing

- [ ] Homepage loads correctly
- [ ] All pages accessible
- [ ] Projects display properly
- [ ] Contact form works and sends emails
- [ ] Dark mode toggle functions
- [ ] Responsive design on mobile
- [ ] All animations working
- [ ] SSL certificate active
- [ ] Admin panel accessible
- [ ] Email notifications working

## 🐛 Troubleshooting

### White Screen of Death
```php
// Enable debugging in wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
```

### Permalinks Not Working
```bash
# Enable mod_rewrite
sudo a2enmod rewrite
sudo service apache2 restart
```

### File Upload Issues
```php
// Increase limits in wp-config.php
@ini_set('upload_max_size', '64M');
@ini_set('post_max_size', '64M');
@ini_set('max_execution_time', '300');
```

### Email Not Sending
- Install "WP Mail SMTP" plugin
- Configure with SMTP credentials

## 📞 Support

For deployment issues:
- Check error logs: `wp-content/debug.log`
- Contact hosting support
- Review WordPress Codex: https://codex.wordpress.org/

## 🎉 Success!

Once deployed, your Smart Portfolio site should be live and fully functional!

Remember to:
- Keep WordPress, theme, and plugins updated
- Regular backups (daily recommended)
- Monitor site performance
- Review security logs
