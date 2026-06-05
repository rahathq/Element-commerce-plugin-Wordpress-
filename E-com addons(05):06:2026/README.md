# E-Com Addons for Elementor & WooCommerce

**E-Com Addons (ECMA)** is a premium, feature-rich Elementor extension plugin designed specifically for WooCommerce stores and dynamic content layout generation. Built using modern WordPress coding standards and AI-assisted workflows, it provides four state-of-the-art interactive widgets, a diagnostic environmental checker, and an admin Widgets Toggle Manager that allows you to selectively load assets to optimize site speed and performance.

---

## 🚀 Key Features

### 1. 🖼️ ECMA - Before After Image Slider
Allows users to compare two images (e.g., product results, photo edits, design variations) using an interactive, touch-friendly drag handler.
- **Interactive Handle**: Drag handle supporting smooth mouse and touch swipe movements.
- **Labels**: Optional "Before" and "After" labels with visibility toggles (always show or show only on hover).
- **Responsive Layout**: Slider direction (horizontal/vertical) and handle position start adjustments.
- **Styling Options**: Color controls for border, handle bar, arrows, labels background, and text.

### 2. 📊 ECMA - Dynamic Data Table
A highly customisable, responsive, and interactive data table designed to display structured data, directories, and specifications.
- **Elementor Repeater Controls**: Manual column configuration and cell coordination to place Text, Images, Icons, Buttons, or Badges in cells.
- **Interactive Search**: Real-time frontend live search filtering rows instantly as you type.
- **Column Sorting**: Click headers to sort rows alphanumerically or numerically.
- **Sticky Headers**: Freeze headers during vertical scrolls with custom top offset controls.
- **Mobile Stack Mode**: A custom responsive mode that stacks columns into distinct cards on mobile viewports for enhanced readability.

### 3. 🛍️ ECMA - Product Gallery Slider
A WooCommerce product gallery replacement widget featuring thumbnail navigation, zoom magnifier effects, and lightboxes.
- **Flexible Thumbnails**: Arrange thumbnails below, to the left, or to the right of the main image.
- **Touch-enabled Carousels**: Built on Swiper.js to support autoplay, loop, drag/swipe, and navigation arrows/pagination dots.
- **Premium Style Controls**: Borders, spacing, active thumbnail borders, and slide transition effects.

### 4. 👁️ ECMA - Recently Viewed Products
Tracks products customers visit and displays them dynamically in a responsive grid or Swiper slider.
- **Caching-Friendly AJAX Rendering**: The widget outputs a skeleton loader and fetches the products loop via WordPress AJAX. This guarantees 100% compatibility with page caching plugins (e.g., WP Rocket, LiteSpeed Cache).
- **Client-side Tracking**: Product IDs are saved to `localStorage` (with a secure cookie fallback).
- **Flexible Queries**: Controls for product counts, chronological order, out-of-stock hiding, and current product exclusion.
- **View Product Button**: Replaces the generic add-to-cart button with a customizer-defined "View Product" button redirecting users to the single product page.

---

## 🛠️ Performance & Administration

### 🎛️ Widgets Toggle Manager
Located under `ECMA Addons > Widgets Manager`, the toggle center allows administrators to turn individual widgets ON or OFF. 
- **Resource Savings**: When a widget is deactivated, its PHP files are skipped, and its CSS/JS files are not registered or enqueued. This keeps your WordPress site footprint minimal and fast.
- **AJAX Settings Saving**: Switch updates are written using secure WordPress AJAX with instant Toast feedback notifications.

### 📋 Diagnostics Panel
An admin control center testing host environments to ensure seamless Operation:
- **PHP Version Check** (Min 7.4.0)
- **WordPress Version Check** (Min 6.0.0)
- **Elementor Plugin State** (Verifies parent installation and activation status)
- **PHP Memory Limit Check** (Checks if memory limits are >= 256MB for optimal Swiper/slider canvas performance)

---

## 🔒 Security & Validation Handling

- **Nonces & Cap Checks**: Every admin dashboard action verifies nonces and ensures the user possesses the `manage_options` capability.
- **Output Escaping**: Every variable echoed is fully escaped using `esc_html()`, `esc_attr()`, `esc_url()`, and `wp_kses_post()`.
- **AJAX Sanitization**: The Recently Viewed Products query processes parameters strictly via array maps and `intval()` casts to prevent SQL injection or bad data lookups.
- **Dependency Protection**: The plugin automatically runs active verification hooks to gracefully self-deactivate and alert the user if parent dependencies (Elementor) are deactivated.

---

## 📂 Folder Structure

```
e-com-addons/
├── assets/
│   ├── css/
│   │   ├── admin-dashboard.css         # Styling for WordPress dashboard & toast alerts
│   │   ├── before-after-slider.css     # Handlebars, labels, and comparisons
│   │   ├── dynamic-data-table.css       # Sortable indicators, sticky headers, mobile cards
│   │   ├── product-gallery-slider.css  # Swiper thumbnail galleries
│   │   └── recently-viewed-products.css# Card layouts, skeleton shimmers, magnifier zooms
│   └── js/
│       ├── admin-dashboard.js          # AJAX widget toggling and toasts
│       ├── before-after-slider.js      # Swipe and drag handler calculations
│       ├── dynamic-data-table.js       # Live search and table sorting
│       ├── product-gallery-slider.js   # Swiper integrations
│       └── recently-viewed-products.js # Client-side LocalStorage tracker & AJAX loader
├── includes/
│   ├── class-e-com-addons.php          # Main plugin loader & resource enqueuer
│   ├── class-ecma-admin.php            # Dashboard panels, diagnostics, and menu registers
│   └── index.php                       # Security block
├── widgets/
│   ├── class-before-after-slider.php   # Slider elementor widget logic
│   ├── class-dynamic-data-table.php    # Data table elementor widget logic
│   ├── class-product-gallery-slider.php# Gallery elementor widget logic
│   └── class-recently-viewed-products.php# Recently viewed widget controls & query callbacks
├── e-com-addons.php                    # Plugin main file and dependency checkers
└── README.md                           # Documentation
```

---

## ⚙️ Installation & Usage

1. **Upload**: Upload the `e-com-addons` plugin directory to your `/wp-content/plugins/` folder.
2. **Activate**: Go to `Plugins > Installed Plugins` in WordPress and click **Activate**.
3. **Configure**: Use `ECMA Addons` in your sidebar to check the Diagnostics panel and toggle widgets.
4. **Build**: Open any page inside Elementor, search for the `ECMA` category widgets, drag them onto your canvas, and configure their properties.
