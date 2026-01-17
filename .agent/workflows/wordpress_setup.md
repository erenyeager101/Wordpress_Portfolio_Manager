---
description: Setup Smart Content & Portfolio Management System on WordPress
---

# WordPress Smart Content & Portfolio Management System Setup Workflow

This workflow guides you through creating a custom WordPress solution that enables admins to manage projects, blogs, and contact leads using custom post types, custom plugins, and an optimized theme.

## Prerequisites
1. **Local Development Environment**
   - PHP 8.2+ (with extensions: `mysqli`, `gd`, `curl`, `mbstring`)
   - MySQL 8.0+ (or MariaDB)
   - Apache/Nginx (or use **LocalWP**, **MAMP**, **XAMPP**)
   - **Node.js** (v20) and **npm** for asset bundling
2. **Git** installed for version control.
3. **Composer** for PHP dependency management.
4. **WP-CLI** (optional but highly recommended).

## Step‑by‑Step Instructions

1. **Create Project Directory**
   ```bash
   mkdir -p C:/TY-3/Projects/Wordpress && cd C:/TY-3/Projects/Wordpress
   ```

2. **Download WordPress Core**
   ```bash
   # Using WP‑CLI (turbo)
   wp core download --path=.
   ```

3. **Create a New Database**
   ```bash
   # Adjust credentials as needed
   mysql -u root -p -e "CREATE DATABASE wp_smart_content CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

4. **Generate wp-config.php**
   ```bash
   wp config create --dbname=wp_smart_content --dbuser=root --dbpass=YOUR_PASSWORD --dbhost=localhost --path=.
   ```

5. **Run the WordPress Installer**
   ```bash
   wp core install --url="http://localhost/wordpress" --title="Smart Content Portfolio" --admin_user=admin --admin_password=StrongPass123! --admin_email=admin@example.com --path=.
   ```

6. **Version Control Initialization**
   ```bash
   git init
   echo "wp-content/uploads" >> .gitignore
   echo "wp-config.php" >> .gitignore   # keep credentials private, commit a sample config instead
   git add .
   git commit -m "Initial WordPress core setup"
   ```

---
### Custom Development
---

7. **Create a Custom Theme**
   - Inside `wp-content/themes/` create a folder `smart-portfolio`.
   - Add `style.css` with theme header, `functions.php`, `index.php`, and template parts.
   - Enqueue a modern CSS reset, Google Font **Inter**, and a custom stylesheet.
   - Use **Sass** (optional) and compile via npm scripts.

8. **Add Theme Boilerplate (turbo)**
   ```bash
   mkdir -p wp-content/themes/smart-portfolio && cd wp-content/themes/smart-portfolio
   cat > style.css <<'EOF'
   /*
   Theme Name: Smart Portfolio
   Theme URI: https://example.com/smart-portfolio
   Author: Your Name
   Author URI: https://example.com
   Description: Premium theme for portfolio & content management with glassmorphism UI.
   Version: 1.0.0
   License: GPLv2 or later
   License URI: https://www.gnu.org/licenses/gpl-2.0.html
   Tags: portfolio, custom-post-type, responsive, dark-mode
   Text Domain: smart-portfolio
   */
   EOF
   ```

9. **Set Up Build Tools**
   - In the theme folder, run:
   ```bash
   npm init -y
   npm i -D sass postcss autoprefixer cssnano
   ```
   - Create `src/scss/main.scss` and compile to `style.css` using an npm script.

10. **Create Custom Post Types**
    - In `functions.php`, register CPTs: `project`, `lead` (for contact leads), and ensure default `post` remains for blogs.
    ```php
    function sp_register_cpts() {
        // Projects CPT
        register_post_type('project', [
            'label' => __('Projects', 'smart-portfolio'),
            'public' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
            'has_archive' => true,
            'rewrite' => ['slug' => 'projects'],
            'menu_icon' => 'dashicons-portfolio',
        ]);
        // Leads CPT (admin‑only)
        register_post_type('lead', [
            'label' => __('Leads', 'smart-portfolio'),
            'public' => false,
            'show_ui' => true,
            'show_in_rest' => true,
            'supports' => ['title', 'custom-fields'],
            'menu_icon' => 'dashicons-email',
        ]);
    }
    add_action('init', 'sp_register_cpts');
    ```

11. **Create a Simple Leads Management Plugin**
    - Path: `wp-content/plugins/lead-manager/lead-manager.php`
    - The plugin adds a contact form (shortcode) that stores submissions as `lead` CPT entries.
    - Include nonce verification and email notification.

12. **Add a Contact Form Shortcode** (inside plugin)
    ```php
    function lm_contact_form() {
        ob_start();
        ?>
        <form id="lead-form" method="post">
            <?php wp_nonce_field('lm_submit_lead', 'lm_nonce'); ?>
            <input type="text" name="name" placeholder="Your Name" required />
            <input type="email" name="email" placeholder="Email" required />
            <textarea name="message" placeholder="Message" required></textarea>
            <button type="submit">Send</button>
        </form>
        <?php
        return ob_get_clean();
    }
    add_shortcode('lead_form', 'lm_contact_form');
    ```

13. **Handle Form Submission**
    ```php
    function lm_handle_submission() {
        if (isset($_POST['lm_nonce']) && wp_verify_nonce($_POST['lm_nonce'], 'lm_submit_lead')) {
            $post_id = wp_insert_post([
                'post_type' => 'lead',
                'post_title' => sanitize_text_field($_POST['name']),
                'post_status' => 'publish',
                'meta_input' => [
                    'email' => sanitize_email($_POST['email']),
                    'message' => sanitize_textarea_field($_POST['message']),
                ],
            ]);
            // Optional: send email notification
        }
    }
    add_action('init', 'lm_handle_submission');
    ```

14. **Activate Theme & Plugin**
    - Log into `/wp-admin`, go to Appearance → Themes, activate **Smart Portfolio**.
    - Go to Plugins → Installed Plugins, activate **Lead Manager**.

---
### UI / Design Enhancements
---

15. **Implement Glassmorphism Layout**
    - In `style.scss`, use backdrop‑filter, semi‑transparent backgrounds, and subtle shadows.
    - Example snippet:
    ```scss
    .card {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        box-shadow: 0 4px 30px rgba(0,0,0,0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    ```

16. **Add Dark Mode Toggle**
    - Enqueue a small JS that toggles `data-theme="dark"` on `<html>` and store preference in `localStorage`.

17. **Responsive Grid for Projects**
    - Use CSS Grid/Flexbox to display project cards, each linking to a single‑project template (`single-project.php`).

---
### Performance & SEO
---

18. **Enable WP‑Rocket (or free caching plugin)** for page caching.
19. **Optimize Images** – install **Smush** or **EWWW Image Optimizer**.
20. **Add SEO Meta Tags** – install **Yoast SEO** and configure defaults.
21. **Add Structured Data** for Projects (JSON‑LD) in `single-project.php`.

---
### Deployment
---

22. **Push to Remote Repository** (GitHub/GitLab).
23. **Set Up CI/CD** – use GitHub Actions to run `npm run build` and deploy via **FTP** or **SSH** to the production server.
24. **Database Migration** – use **WP Migrate DB** or **All‑in‑One WP Migration**.

---
### Final Checklist
---
- [ ] WordPress core installed and functional.
- [ ] Custom theme `smart-portfolio` activated.
- [ ] CPTs `project` and `lead` registered.
- [ ] Lead‑manager plugin active and contact form working.
- [ ] Glassmorphism UI with dark mode.
- [ ] SEO and performance plugins configured.
- [ ] Deployment pipeline ready.

---

**You can now follow this workflow step‑by‑step.** Feel free to ask for any specific code snippets, deeper configuration details, or help automating any of the steps.
