# Chhankitek Documentation Site

This directory contains the static documentation website for the Chhankitek Laravel package.

## Tech Stack

- **HTML5** - Semantic markup
- **Tailwind CSS** - Utility-first CSS framework (via CDN)
- **JavaScript** - Smooth scrolling and interactions
- **Netlify** - Static site hosting

## Structure

```
docs/
├── index.html           # Home page
├── documentation.html   # Full documentation
├── netlify.toml        # Netlify configuration
├── _redirects          # Netlify redirect rules
└── README.md           # This file
```

## Local Development

Simply open the HTML files in your browser. Since we're using Tailwind CSS via CDN, no build step is required.

```bash
# Open in default browser (Linux)
xdg-open index.html

# Or use a local server (recommended)
python3 -m http.server 8000
# Then visit http://localhost:8000
```

## Deployment to Netlify

### Option 1: Deploy via Netlify UI

1. Log in to [Netlify](https://app.netlify.com/)
2. Click "Add new site" → "Import an existing project"
3. Connect your GitHub repository
4. Set build settings:
   - **Base directory**: `docs`
   - **Build command**: (leave empty)
   - **Publish directory**: `.` (current directory)
5. Click "Deploy site"

### Option 2: Deploy via Netlify CLI

```bash
# Install Netlify CLI
npm install -g netlify-cli

# Login to Netlify
netlify login

# Deploy from the docs directory
cd docs
netlify deploy --prod
```

### Option 3: Drag and Drop

1. Go to [Netlify Drop](https://app.netlify.com/drop)
2. Drag the `docs` folder onto the page
3. Your site will be deployed instantly

## Custom Domain

To add a custom domain:

1. Go to your site's settings in Netlify
2. Click "Domain management"
3. Click "Add custom domain"
4. Follow the instructions to configure your DNS

## Features

- ✅ Responsive design (mobile, tablet, desktop)
- ✅ Smooth scrolling navigation
- ✅ Code syntax highlighting
- ✅ SEO optimized
- ✅ Fast loading (CDN-based assets)
- ✅ Security headers configured
- ✅ Clean URLs

## Customization

### Updating Content

- Edit `index.html` for home page changes
- Edit `documentation.html` for documentation updates
- Both files use Tailwind CSS utility classes for styling

### Changing Colors

The site uses Tailwind's default color palette. To change the primary color:

1. Find all instances of `indigo-` in the HTML files
2. Replace with your preferred color (e.g., `blue-`, `purple-`, `green-`)

### Adding Pages

1. Create a new HTML file in the `docs` directory
2. Copy the navigation structure from existing pages
3. Add a link to the new page in the navigation menu
4. The page will automatically be deployed with the rest of the site

## Performance

The site is optimized for performance:

- Minimal external dependencies (only Tailwind CSS and Google Fonts)
- No JavaScript frameworks
- Cached assets via CDN
- Optimized images (none currently, but use WebP format if adding)

## Support

For issues related to the documentation site, please open an issue on the [GitHub repository](https://github.com/HELMAB/chhankitek/issues).
