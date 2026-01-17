# Smart Content & Portfolio Management System

A premium WordPress theme and plugin solution for managing projects, blogs, and contact leads with a stunning glassmorphism UI.

## 🎨 Features

### Theme Features
- **Modern Glassmorphism Design** - Beautiful frosted glass effects with backdrop blur
- **Dark Mode Support** - Automatic theme switching with localStorage persistence
- **Responsive Layout** - Mobile-first design that looks great on all devices
- **Custom Post Types** - Projects and Leads CPTs with rich metadata
- **Advanced Animations** - Scroll reveal, parallax, card tilt, and micro-interactions
- **SEO Optimized** - Semantic HTML5, proper heading structure, and meta tags
- **Performance Focused** - Optimized assets, lazy loading, and efficient code

### Plugin Features (Lead Manager)
- **Contact Form** - Beautiful, accessible contact form with validation
- **Lead Management** - All submissions saved as custom post types
- **Email Notifications** - Instant admin notifications for new leads
- **Admin Interface** - Easy-to-use dashboard for managing leads
- **Shortcode Support** - `[lead_form]` or `[contact_form]`

## 📁 Project Structure

```
wp-content/
├── themes/
│   └── smart-portfolio/
│       ├── src/
│       │   └── scss/
│       │       └── main.scss          # Source SCSS with design system
│       ├── assets/
│       │   └── css/
│       │       └── main.css           # Compiled CSS
│       ├── js/
│       │   ├── main.js                # Main JavaScript
│       │   ├── theme-toggle.js        # Dark mode functionality
│       │   └── animations.js          # Animation library
│       ├── template-parts/            # Reusable template parts
│       ├── functions.php              # Theme functions
│       ├── header.php                 # Header template
│       ├── footer.php                 # Footer template
│       ├── index.php                  # Main template
│       ├── single.php                 # Single post template
│       ├── single-project.php         # Single project template
│       ├── archive-project.php        # Project archive template
│       ├── style.css                  # Theme header
│       └── package.json               # Build configuration
│
└── plugins/
    └── lead-manager/
        ├── assets/
        │   ├── css/
        │   │   └── lead-manager.css   # Plugin styles
        │   └── js/
        │       └── lead-manager.js    # Plugin scripts
        └── lead-manager.php           # Main plugin file
```

## 🚀 Installation

### Prerequisites
- PHP 8.0 or higher
- MySQL 5.7 or higher
- WordPress 6.0 or higher
- Node.js 18+ (for development)

### Setup Steps

1. **Install WordPress**
   - Download WordPress from [wordpress.org](https://wordpress.org/download/)
   - Set up your database
   - Complete the WordPress installation

2. **Install the Theme**
   ```bash
   # Copy the theme to your WordPress installation
   cp -r wp-content/themes/smart-portfolio /path/to/wordpress/wp-content/themes/
   
   # Navigate to the theme directory
   cd /path/to/wordpress/wp-content/themes/smart-portfolio
   
   # Install dependencies
   npm install
   
   # Build CSS from SCSS
   npm run build
   ```

3. **Install the Plugin**
   ```bash
   # Copy the plugin to your WordPress installation
   cp -r wp-content/plugins/lead-manager /path/to/wordpress/wp-content/plugins/
   ```

4. **Activate Theme and Plugin**
   - Log in to WordPress admin (`/wp-admin`)
   - Go to **Appearance → Themes** and activate **Smart Portfolio**
   - Go to **Plugins** and activate **Lead Manager**

## 🎯 Usage

### Creating Projects

1. Go to **Projects → Add New** in WordPress admin
2. Fill in the project details:
   - Title
   - Description (content editor)
   - Featured image
   - Project URL
   - GitHub URL
   - Technologies used
   - Client name
   - Completion date
3. Publish the project

### Adding a Contact Form

Add the contact form to any page using the shortcode:

```
[lead_form]
```

Or with custom title and subtitle:

```
[lead_form title="Contact Us" subtitle="We'd love to hear from you!"]
```

### Managing Leads

1. Go to **Leads** in WordPress admin
2. View all contact form submissions
3. Click on any lead to see full details
4. Email notifications are sent automatically

### Customizing the Theme

#### Development Mode (with file watching)
```bash
cd wp-content/themes/smart-portfolio
npm run dev
```

This will watch for SCSS changes and automatically recompile.

#### Production Build
```bash
npm run build
```

This creates a minified CSS file for production.

## 🎨 Design System

The theme uses a comprehensive design system with CSS custom properties:

### Colors
- Primary: `hsl(250, 84%, 54%)` - Vibrant purple
- Secondary: `hsl(340, 82%, 52%)` - Pink accent
- Accent: `hsl(171, 100%, 41%)` - Teal highlight

### Typography
- Font Family: Inter (Google Fonts)
- Font Sizes: Fluid scale from 0.75rem to 4rem
- Font Weights: 300, 400, 500, 600, 700, 800

### Spacing
- XS: 0.5rem
- SM: 1rem
- MD: 1.5rem
- LG: 2rem
- XL: 3rem
- 2XL: 4rem
- 3XL: 6rem

### Glassmorphism
- Background: `rgba(255, 255, 255, 0.25)`
- Backdrop Blur: 10px
- Border: `rgba(255, 255, 255, 0.18)`

## 🌙 Dark Mode

Dark mode is automatically enabled based on:
1. User preference (toggle button in header)
2. System preference (if no user preference set)

The theme uses `data-theme="dark"` attribute on the `<html>` element.

## 📱 Responsive Breakpoints

- Mobile: < 768px
- Tablet: 768px - 1024px
- Desktop: > 1024px

## 🔧 Customization

### Adding Custom Colors

Edit `src/scss/main.scss`:

```scss
:root {
  --color-custom: hsl(200, 80%, 50%);
}
```

### Adding Custom Fonts

Edit `functions.php`:

```php
wp_enqueue_style('custom-font', 'https://fonts.googleapis.com/css2?family=YourFont:wght@400;700&display=swap');
```

### Creating Custom Templates

Create a new file in the theme directory:

```php
<?php
/**
 * Template Name: Custom Page
 */
get_header();
// Your custom template code
get_footer();
?>
```

## 🚀 Deployment

### Using Local Development Environment

1. **LocalWP** (Recommended for Windows)
   - Download from [localwp.com](https://localwp.com/)
   - Create a new site
   - Copy theme and plugin files
   - Activate and configure

2. **XAMPP**
   - Install XAMPP
   - Place WordPress in `htdocs`
   - Configure database
   - Install theme and plugin

### Production Deployment

1. Export your local database
2. Upload WordPress files via FTP/SFTP
3. Import database on production server
4. Update `wp-config.php` with production credentials
5. Update site URL in database or use WP-CLI:
   ```bash
   wp search-replace 'http://localhost' 'https://yourdomain.com'
   ```

## 📄 License

This project is licensed under the GPL v2 or later.

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📧 Support

For support, email your-email@example.com or create an issue in the repository.

## 🎉 Credits

- **Theme Design**: Modern glassmorphism with dark mode
- **Fonts**: Inter by Google Fonts
- **Icons**: (Add your icon library here)

---

**Built with ❤️ using WordPress**
