# EasyCart – Modern Grocery E-Commerce Platform

A professional, full-featured grocery delivery web application built with **PHP, HTML5, and CSS3**, featuring dynamic shopping cart functionality with real-time price calculations and GST tax compliance.

---

## 📋 Project Overview

**EasyCart** is a modern e-commerce platform designed for grocery delivery services, inspired by industry leaders like Zepto, Blinkit, and Instamart. The platform combines a clean, professional UI with robust backend functionality for seamless shopping experiences.

### Technical Stack
- **Frontend:** HTML5, CSS3
- **Backend:** PHP (Server-side Processing)
- **Sessions:** PHP Session Management
- **Database:** Static Product Data (Easily convertible to database)
- **CSS Architecture:** Centralized styling (styles.css)
- **Responsive Design:** Mobile-first approach
- **JavaScript (AJAX):** Dynamic cart updates without reload

---

## 📁 Project Structure

```
EasyCart/
├── index.php                  # Home page with featured products
├── products.php               # Dynamic product catalog with search
├── product-detail.php         # Individual product page with details
├── cart.php                   # Shopping cart with real-time calculations
├── checkout.php               # Order checkout with delivery options
├── login.php                  # User login form
├── signup.php                 # User registration form
├── orders.php                 # Order history and tracking
├── styles.css                 # Centralized stylesheet
├── images/                    # Product and hero images
└── README.md                  # Project documentation
```

---

## 📄 Core Files & Functionality

### Backend Pages (PHP)

| File | Purpose | Features |
|------|---------|----------|
| `index.php` | Landing page | Hero slider, featured products, categories |
| `products.php` | Product catalog | Dynamic grid, search functionality, product filtering |
| `product-detail.php` | Single product view | Full product details, add to cart functionality |
| `cart.php` | Shopping cart | Session-based cart, qty adjustment (+/-), real-time calculations, GST billing |
| `checkout.php` | Order processing | Delivery address, payment summary, order confirmation |
| `login.php` | User authentication | Login form with validation |
| `signup.php` | User registration | Registration form with email & password |
| `orders.php` | Order history | User order tracking and history |

### Frontend Files

| File | Purpose |
|------|---------|
| `styles.css` | Complete styling for all pages |

### Media Assets

#### Product Images (10 products)
```
images/
├── apple.jpg          # Fresh Apples (₹120/kg)
├── banana.jpg         # Yellow Bananas (₹60/6pcs)
├── bread.jpg          # Whole Wheat Bread (₹35/400g)
├── chips.jpg          # Potato Chips (₹45/200g)
├── eggs.jpg           # Brown Eggs (₹72/12pcs)
├── milk.jpg           # Fresh Milk (₹65/1L)
├── oil.jpg            # Cooking Oil (₹210/1L)
├── onion.jpg          # Fresh Onions (₹40/kg)
├── rice.jpg           # Basmati Rice (₹450/5kg)
└── tomato.jpg         # Ripe Tomatoes (₹50/kg)
```

#### Hero Slider Images (3 banners)
```
images/
├── hero1.jpg          # Fresh Groceries in 30 Minutes
├── hero2.jpg          # Daily Essentials at Best Prices
└── hero3.jpg          # 100% Organic & Farm Fresh
```

---

## 🎯 Key Features

### Shopping Cart System
✅ **Session-based cart management** - Persistent across page navigation  
✅ **Quantity adjustment** - (+) and (-) buttons with session updates  
✅ **Remove items** - Delete items directly from cart  
✅ **Automatic price calculation** - Item totals update instantly  
✅ **GST tax calculation** - 18% GST applied on (Subtotal + Shipping)  
✅ **Shipping options** - Standard, Express, White Glove, Freight  
✅ **Shipping rules** - Rule-based charges per method  
✅ **Complete billing breakdown** - Subtotal, shipping, GST, and total

### Product Management
✅ **Dynamic product display** - 10 products with images and pricing  
✅ **Search functionality** - Real-time product search  
✅ **Product details** - Comprehensive product information page  
✅ **Add to cart** - Single-click add to cart functionality

### User Experience
✅ **Responsive design** - Mobile, tablet, and desktop support  
✅ **Professional UI/UX** - Clean, modern interface  
✅ **Consistent navigation** - Header navigation across all pages  
✅ **Visual feedback** - Hover effects, active states, smooth transitions  
✅ **AJAX cart updates** - Dynamic cart operations without reload

---

## 🏗️ Page Architecture & Functionality
- **Product cards** with:
  - Product image
  - Product name
  - Quantity info
  - Price
  - "View Details" link
- **Responsive design** (mobile-friendly)

### Product Detail Page (product-detail.php)
- **Single product showcase** - Large product image
- **Comprehensive information:**
  - Product name and pricing
  - Detailed description
  - Quantity/measurement
  - Product specifications
- **Add to cart** - Session-based cart addition
- **Stock status** - Availability information

### Shopping Cart (cart.php)
- **Session-managed cart** - Persistent cart data storage
- **Dynamic cart table** with:
  - Product names
  - Quantity selectors (+/- buttons)
  - Unit price
  - Automatic item total calculation
  - Delete button per item
- **Order Summary Panel:**
  - Subtotal calculation
  - Shipping options with rule-based charges
  - Subtotal before tax
  - GST (18%) calculation
  - Final payable amount
- **Checkout button** - Proceeds to checkout
- **Continue shopping** - Returns to products

### Checkout Page (checkout.php)
- **Order summary recap** - Cart items overview
- **Delivery options:**
  - Standard Shipping
  - Express Shipping
  - White Glove Delivery
  - Freight Shipping
- **Address entry** - Customer delivery address
- **Payment summary** - Final billing breakdown
- **Place order button** - Order confirmation

### Authentication Pages
- **Login (login.php)** - User authentication form
- **Signup (signup.php)** - New user registration
- **Orders (orders.php)** - Order history and tracking

---

## 🎨 Design System

### Color Palette
- **Primary Green:** `#10b981` - Primary action buttons & highlights
- **Dark Green:** `#059669` - Hover states & accents
- **Secondary Green:** `#1b7c5b` - Text links
- **Light Gray:** `#f3f4f6` - Backgrounds & borders
- **White:** `#ffffff` - Card backgrounds
- **Dark Text:** `#1f2937` - Primary text
- **Light Text:** `#6b7280` - Secondary text
- **Red:** `#ef4444` - Delete actions & alerts

### Typography
- **Font Family:** System fonts for optimal performance
- **Headings:** Bold, large font sizes for hierarchy
- **Body Text:** Medium font size (0.95-1rem) for readability
- **Buttons:** Font-weight 600 for emphasis

### Layout Components
- **Flexbox** - Navigation, headers, and spacing
- **CSS Grid** - Product displays and layouts
- **Responsive Media Queries** - Mobile (480px), Tablet (768px), Desktop
- **Shadow Effects** - Card depth (0 2px 4px rgba)
- **Transitions** - Smooth animations (0.3s ease)

### Special Features
- **Navigation active indicator** (green underline + bold)
- **CSS-only hero slider** (no JavaScript)
- **Hover effects** on buttons and cards
- **Smooth transitions** (0.3s ease)
- **Product card shadows** and scale effects
- **Status badges** for orders
- **Form focus states**

---

## Navigation Structure

All pages have consistent header with links:

```
Home → Products → Cart → Login
```

**Active Page Indicators:**
- Home page: "Home" link is highlighted
- Products page: "Products" link is highlighted
- Cart page: "Cart" link is highlighted
- All auth pages: "Login" link is highlighted

---

## Key Features

✅ **Static UI** - No backend required
✅ **HTML5 Semantic** - Proper structure
✅ **CSS-Only Slider** - No JavaScript
✅ **Indian GST Billing** - Tax-compliant
✅ **Professional Design** - Mentor-ready
✅ **Mobile Responsive** - Works on all devices
✅ **Grocery UX** - Domain-specific text & flows
✅ **Clean Code** - Well-organized & readable

---

## How to Use

1. **Create folder structure:**
   ```
   copyyyyyy/
   ├── (all HTML files)
   ├── styles.css
   └── images/
   ```

2. **Add all 13 images** to `/images` folder

3. **Open in browser:**
   - Right-click `index.html` → Open with browser
   - OR drag `index.html` to browser window

4. **Navigate** through pages using header links

---

## 🔧 Technical Implementation

### Backend Logic (PHP)
- **Session Management:** `session_start()` for cart persistence
- **Static Product Database:** PHP array containing 10 products
- **Cart Operations:**
  - Add to cart via product detail page
  - Increase/decrease quantity (+/- buttons)
  - Remove items from cart
  - Calculate totals and taxes
- **Form Handling:** POST method for all cart operations
- **Tax Calculation:** 18% GST applied on (Subtotal + Shipping)

### Frontend Architecture
- **Semantic HTML5** - Proper document structure
- **CSS-Only Styling** - No preprocessors required
- **Responsive Images** - Product photos in images folder
- **Form Elements** - HTML form controls with POST submission
- **Vanilla JS + AJAX** - Cart updates without page reloads

---

## 📱 Responsive Design

| Device | Width | Columns | Adjustments |
|--------|-------|---------|------------|
| Desktop | 1200px+ | 4 | Full layout |
| Laptop | 992px-1199px | 4 | Slight padding reduction |
| Tablet | 768px-991px | 2-3 | Adjusted spacing |
| Mobile | Below 768px | 1-2 | Stacked layout, full width |

---

## 🚀 Setup & Installation

### Prerequisites
- PHP 7.4+ (with built-in server or Apache/XAMPP)
- Modern web browser
- All 13 images in `/images` folder

### Installation Steps

1. **Download project files** to your web directory:
   ```
   /xampp/htdocs/EasyCart/  (or equivalent)
   ```

2. **Add all images** to `/images` folder (13 total)

3. **Start PHP server** (XAMPP/local server)

4. **Open in browser:**
   ```
   http://localhost/EasyCart/index.php
   ```

5. **Navigate** through pages using header menu

### Running Locally
**Using XAMPP:**
```
Control Panel → Apache (Start)
Browser → http://localhost/EasyCart/index.php
```

**Using PHP Built-in Server:**
```
cd /path/to/EasyCart
php -S localhost:8000
Browser → http://localhost:8000
```

---

## 📦 Deployment Checklist

### Pre-Deployment
- [ ] All 13 images optimized and placed in `/images` folder
- [ ] All PHP files reviewed for production readiness
- [ ] Tested on Chrome, Firefox, Safari, Edge browsers
- [ ] Mobile responsiveness verified
- [ ] Cart calculations verified (including GST)
- [ ] Session management tested

### Image Assets Required
- [x] 10 product images (apple, banana, bread, etc.)
- [x] 3 hero slider images
- [x] All images optimized for web (compressed)

### Code Quality
- [x] HTML5 semantic markup
- [x] CSS properly organized
- [x] PHP follows best practices
- [x] Code is commented where needed
- [x] No console errors in browser

---

## 🌟 Best Practices Implemented

✅ **Session Security** - Cart data stored server-side via sessions  
✅ **Input Validation** - Form data sanitized and validated  
✅ **Error Handling** - Graceful fallbacks for edge cases  
✅ **Performance** - Optimized CSS and minimal HTTP requests  
✅ **Accessibility** - Semantic HTML for screen readers  
✅ **SEO Friendly** - Proper meta tags and structure  
✅ **Mobile First** - Responsive design approach  
✅ **Code Organization** - Clean, maintainable code structure

---

## 📊 Product Catalog

| ID | Product | Price | Quantity |
|----|---------|-------|----------|
| 1 | Fresh Apples | ₹120 | 1 kg |
| 2 | Yellow Bananas | ₹60 | 6 pcs |
| 3 | Fresh Milk | ₹65 | 1 L |
| 4 | Whole Wheat Bread | ₹35 | 400g |
| 5 | Basmati Rice | ₹450 | 5 kg |
| 6 | Cooking Oil | ₹210 | 1 L |
| 7 | Brown Eggs | ₹72 | 12 pcs |
| 8 | Fresh Onions | ₹40 | 1 kg |
| 9 | Ripe Tomatoes | ₹50 | 1 kg |
| 10 | Potato Chips | ₹45 | 200g |

---

## 🔐 Security Considerations

- Session-based authentication framework in place
- Form data properly validated
- Output escaping for XSS prevention
- No sensitive data hardcoded
- Ready for database integration

---

## 🎯 Future Enhancements

- Database integration for products and orders
- User authentication system
- Payment gateway integration
- Order tracking system
- Admin dashboard
- Email notifications
- Wishlist functionality
- Product reviews and ratings

---

## 📝 Browser Support

| Browser | Status | Version |
|---------|--------|---------|
| Chrome | ✅ Supported | Latest |
| Firefox | ✅ Supported | Latest |
| Safari | ✅ Supported | Latest |
| Edge | ✅ Supported | Latest |
| Opera | ✅ Supported | Latest |

---

## 📄 License & Usage

**License:** Educational & Portfolio Use  
**Modifications:** Allowed with attribution  
**Commercial Use:** Contact for permissions

---

## 👥 Project Information

**Platform:** EasyCart Grocery Delivery  
**Current Phase:** Phase 2 (Backend Integration)  
**Status:** ✅ Active Development  
**Last Updated:** January 22, 2026  
**Version:** 2.0

---

## 📞 Support & Maintenance

For issues, feature requests, or technical support, review the code or contact the development team.

---

**EasyCart © 2026 - Professional Grocery E-Commerce Solution**
