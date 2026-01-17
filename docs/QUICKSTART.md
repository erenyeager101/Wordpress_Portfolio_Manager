# 🚀 Quick Start Guide

Get your Smart Portfolio WordPress site up and running in minutes!

## ⚡ Fastest Way to Get Started (Windows)

### Step 1: Install LocalWP (5 minutes)

1. **Download LocalWP**
   - Visit: https://localwp.com/
   - Click "Download for Windows"
   - Install the application

2. **Create Your Site**
   - Open LocalWP
   - Click "+ Create a new site"
   - Site name: `Smart Portfolio`
   - Environment: Choose "Preferred"
   - WordPress username: `admin`
   - WordPress password: (choose a strong password)
   - Click "Add Site"

### Step 2: Install Theme & Plugin (2 minutes)

1. **Locate Your Site Folder**
   - In LocalWP, right-click your site
   - Click "Reveal in Explorer"
   - Navigate to: `app/public/wp-content/`

2. **Copy Theme**
   - Copy folder: `C:\TY-3\Projects\Wordpress\wp-content\themes\smart-portfolio`
   - Paste into: `[your-site]/app/public/wp-content/themes/`

3. **Copy Plugin**
   - Copy folder: `C:\TY-3\Projects\Wordpress\wp-content\plugins\lead-manager`
   - Paste into: `[your-site]/app/public/wp-content/plugins/`

### Step 3: Activate Everything (1 minute)

1. **Open WordPress Admin**
   - In LocalWP, click "WP Admin" button
   - Login with your credentials

2. **Activate Theme**
   - Go to: **Appearance → Themes**
   - Find "Smart Portfolio"
   - Click "Activate"

3. **Activate Plugin**
   - Go to: **Plugins → Installed Plugins**
   - Find "Lead Manager"
   - Click "Activate"

### Step 4: Configure Your Site (3 minutes)

1. **Set Permalinks**
   - Go to: **Settings → Permalinks**
   - Select: "Post name"
   - Click "Save Changes"

2. **Create Pages**
   - Go to: **Pages → Add New**
   - Create these pages:
     - **Home** (use "Home Page" template)
     - **Projects** (leave blank, will show project archive)
     - **Blog** (leave blank)
     - **Contact** (add shortcode: `[lead_form]`)

3. **Set Front Page**
   - Go to: **Settings → Reading**
   - Select "A static page"
   - Front page: Home
   - Posts page: Blog
   - Click "Save Changes"

4. **Create Menu**
   - Go to: **Appearance → Menus**
   - Create new menu: "Primary Menu"
   - Add pages: Home, Projects, Blog, Contact
   - Check "Primary Menu" location
   - Click "Save Menu"

### Step 5: Add Sample Content (5 minutes)

1. **Create a Project**
   - Go to: **Projects → Add New**
   - Title: "Sample Project"
   - Add description in editor
   - Set featured image (upload any image)
   - Fill in Project Details:
     - Project URL: https://example.com
     - Technologies: React, Node.js, MongoDB
     - Client: Sample Client
     - Completion Date: (today's date)
   - Click "Publish"

2. **Create a Blog Post**
   - Go to: **Posts → Add New**
   - Title: "Welcome to Our Blog"
   - Add some content
   - Set featured image
   - Click "Publish"

### Step 6: View Your Site! 🎉

1. In LocalWP, click "Open site"
2. Your beautiful portfolio site is live!

## 🎨 Customize Your Site

### Change Colors

Edit: `wp-content/themes/smart-portfolio/src/scss/main.scss`

```scss
:root {
  --color-primary: hsl(250, 84%, 54%);    // Change this!
  --color-secondary: hsl(340, 82%, 52%);  // And this!
  --color-accent: hsl(171, 100%, 41%);    // And this!
}
```

Then rebuild:
```bash
cd wp-content/themes/smart-portfolio
npm run build
```

### Change Site Title & Tagline

1. Go to: **Settings → General**
2. Update "Site Title" and "Tagline"
3. Click "Save Changes"

### Add Social Links

Edit: `wp-content/themes/smart-portfolio/footer.php`

Find the social links section and update URLs.

## 📝 Common Tasks

### Add Contact Form to Any Page

Just add this shortcode:
```
[lead_form]
```

Or with custom text:
```
[lead_form title="Get in Touch" subtitle="Let's talk about your project"]
```

### View Contact Form Submissions

1. Go to: **Leads** in WordPress admin
2. Click on any lead to view details
3. Email notifications are sent automatically

### Toggle Dark Mode

Click the moon/sun icon in the header (added automatically by JavaScript)

### Add More Projects

1. Go to: **Projects → Add New**
2. Fill in all fields
3. Publish!

## 🔧 Development Mode

Want to make design changes? Run the theme in development mode:

```bash
cd wp-content/themes/smart-portfolio
npm run dev
```

This will watch for SCSS changes and auto-compile!

## 🐛 Troubleshooting

### Theme Not Showing Up?
- Make sure you copied the entire `smart-portfolio` folder
- Check folder is in: `wp-content/themes/`

### Plugin Not Working?
- Make sure you copied the entire `lead-manager` folder
- Check folder is in: `wp-content/plugins/`
- Activate it in WordPress admin

### Contact Form Not Sending Emails?
- Check your WordPress email settings
- Install "WP Mail SMTP" plugin for reliable email delivery

### Styles Not Loading?
- Make sure you ran `npm install` and `npm run build`
- Check that `assets/css/main.css` exists

### Dark Mode Not Working?
- Clear your browser cache
- Check browser console for JavaScript errors

## 📚 Learn More

- **Full Documentation**: See `README.md`
- **Deployment Guide**: See `DEPLOYMENT.md`
- **Project Summary**: See `PROJECT_SUMMARY.md`

## 🎉 You're Done!

Your Smart Portfolio WordPress site is now live and ready to use!

### What You Can Do Now:

- ✅ Add more projects
- ✅ Write blog posts
- ✅ Customize colors and fonts
- ✅ Add your own content
- ✅ Test the contact form
- ✅ Toggle dark mode
- ✅ Share with the world!

---

**Need Help?** Check the full documentation in README.md or DEPLOYMENT.md

**Happy Building! 🚀**
