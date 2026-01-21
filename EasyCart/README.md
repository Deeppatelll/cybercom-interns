# EasyCart – Grocery E-Commerce Website

A modern, professional grocery delivery website UI built with **HTML5 & CSS3 only** (static, no JavaScript).

---

## Project Overview

**EasyCart** is a static HTML + CSS e-commerce platform inspired by popular grocery delivery apps like Zepto, Blinkit, and Instamart.

- **Tech Stack:** HTML5 + CSS3 (ONLY)
- **Backend:** None (Static UI)
- **JavaScript:** None
- **Frameworks:** None
- **Styling:** Single CSS file (styles.css)

---

## Project Structure

```
copyyyyyy/
├── index.html                 # Home page with hero slider
├── products.html              # Product listing grid
├── product-detail.html        # Individual product details
├── cart.html                  # Shopping cart with billing
├── checkout.html              # Checkout page with delivery options
├── login.html                 # Login form
├── signup.html                # Sign up form
├── orders.html                # Order history table
├── styles.css                 # Main stylesheet (ALL styling)
├── images/                    # Folder for product images
└── README.md                  # This file
```

---

## Required Files

### HTML Files (8 total)

| File | Purpose |
|------|---------|
| `index.html` | Home page with CSS-only slider (3 hero banners) |
| `products.html` | Grid display of 10+ grocery products |
| `product-detail.html` | Single product page with details |
| `cart.html` | Shopping cart with GST billing breakdown |
| `checkout.html` | Delivery address & order summary |
| `login.html` | User login form |
| `signup.html` | User registration form |
| `orders.html` | Static order history table |

### CSS File (1 total)

| File | Purpose |
|------|---------|
| `styles.css` | All styling for all pages (1 centralized file) |

### Image Files (Required in `/images` folder)

#### Product Images (10 total)

```
images/
├── apple.jpg          # Fresh Apples (1kg)
├── banana.jpg         # Yellow Bananas (6 pcs)
├── bread.jpg          # Whole Wheat Bread (400g)
├── chips.jpg          # Potato Chips (200g)
├── eggs.jpg           # Brown Eggs (12 pcs)
├── milk.jpg           # Fresh Milk (1 Liter)
├── oil.jpg            # Cooking Oil (1 Liter)
├── onion.jpg          # Fresh Onions (1kg)
├── rice.jpg           # Basmati Rice (5kg)
└── tomato.jpg         # Ripe Tomatoes (1kg)
```

#### Hero Slider Images (3 total)

```
images/
├── hero1.jpg          # Fresh Groceries in 30 Minutes
├── hero2.jpg          # Daily Essentials at Best Prices
└── hero3.jpg          # 100% Organic & Farm Fresh
```

**Total Images Required: 13 images**

---

## File Details & Features

### Home Page (index.html)
- **CSS-only slider** with 3 hero banners (radio button technique)
- **Navigation bar** with active page indicator
- **Featured products section** (6 products)
- **Categories grid** (Fruits, Dairy, Snacks, Staples)
- **Popular brands section**
- **Footer**

### Products Page (products.html)
- **Grid layout** with 10 grocery products
- **Product cards** with:
  - Product image
  - Product name
  - Quantity info
  - Price
  - "View Details" link
- **Responsive design** (mobile-friendly)

### Product Detail Page (product-detail.html)
- **Single product image**
- **Product name & pricing**
- **Detailed description**
- **Product information:**
  - Freshness guarantee
  - Best before date
  - Storage instructions
  - Source/origin
  - Delivery time
  - Stock status
- **Add to Cart button**

### Cart Page (cart.html)
- **Static cart items** (5 products)
- **Cart table** with:
  - Product name
  - Quantity
  - Unit price
  - Total price
- **Order Summary** with:
  - Subtotal: ₹946
  - Delivery: ₹49
  - **GST (5%): ₹47.30**
  - **Total Payable: ₹993.30**
- **Tax compliance note**
- **Grocery-specific UX text**

### Checkout Page (checkout.html)
- **Shipping address form** (static fields)
- **Delivery options:**
  - Express (30 min) - ₹49
  - Standard (1-2 hrs) - ₹29
  - Scheduled (Next day) - Free
- **Order summary** (same as cart)
- **Place Order button** → orders.html

### Login Page (login.html)
- **Email input**
- **Password input**
- **Login button**
- **Link to signup**
- **Security note**

### Sign Up Page (signup.html)
- **Full name input**
- **Email input**
- **Password input**
- **Confirm password input**
- **Sign up button**
- **Link to login**

### Orders Page (orders.html)
- **Order history table** with:
  - Order ID
  - Date
  - Items ordered
  - Amount paid
  - Delivery status (Delivered)
- **8 static orders** with varying amounts
- **Help section**

---

## CSS Features

### Color Scheme
- **Primary Green:** `#10b981`
- **Dark Green:** `#059669`
- **Light Gray:** `#f3f4f6`
- **White:** `#ffffff`
- **Dark Text:** `#1f2937`

### Layout Components
- **Flexbox** for navigation and spacing
- **CSS Grid** for product displays
- **Responsive design** with media queries
- **Mobile breakpoints:** 768px, 480px

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

## Responsive Breakpoints

| Screen Size | Hero Height | Grid Columns | Font Size |
|-------------|------------|--------------|-----------|
| Desktop | 500px | 4 columns | Large |
| Tablet (768px) | 350px | 2-3 columns | Medium |
| Mobile (480px) | 280px | 1-2 columns | Small |

---

## Browser Compatibility

✅ Chrome (Latest)
✅ Firefox (Latest)
✅ Safari (Latest)
✅ Edge (Latest)

**Note:** Requires support for:
- CSS Flexbox
- CSS Grid
- CSS Transitions
- `:checked` pseudo-class

---

## Design Inspiration

- **Zepto** - Fast delivery branding
- **Blinkit** - Modern green color scheme
- **Instamart** - Clean card layouts

---

## Internship Assignment

**Created for:** Cybercom Creation  
**Project:** EasyCart – Phase 1 (Static UI)  
**Level:** Junior Developer / Intern  
**Tech:** HTML5 + CSS3 (No JS)

---

## File Checklist

### HTML Files
- [ ] index.html
- [ ] products.html
- [ ] product-detail.html
- [ ] cart.html
- [ ] checkout.html
- [ ] login.html
- [ ] signup.html
- [ ] orders.html

### CSS File
- [ ] styles.css

### Images (13 total)
#### Product Images
- [ ] images/apple.jpg
- [ ] images/banana.jpg
- [ ] images/bread.jpg
- [ ] images/chips.jpg
- [ ] images/eggs.jpg
- [ ] images/milk.jpg
- [ ] images/oil.jpg
- [ ] images/onion.jpg
- [ ] images/rice.jpg
- [ ] images/tomato.jpg

#### Hero Slider Images
- [ ] images/hero1.jpg
- [ ] images/hero2.jpg
- [ ] images/hero3.jpg

---

## Notes for Interns

1. **CSS-Only Slider:** Hero section uses HTML radio buttons + CSS `:checked` pseudo-class. No JavaScript needed!

2. **Semantic HTML:** All pages use proper semantic tags (`<header>`, `<nav>`, `<main>`, `<section>`, `<footer>`).

3. **Hardcoded Data:** All product info and prices are static. Perfect for static portfolio projects.

4. **Professional Layout:** Inspired by real e-commerce apps. Great for interviews!

5. **Mobile-First Approach:** CSS grid and flexbox handle all screen sizes.

---

## License

Free to use for educational & portfolio purposes.

---

**Version:** 1.0  
**Last Updated:** January 21, 2026  
**Status:** ✅ Complete - Ready for deployment
