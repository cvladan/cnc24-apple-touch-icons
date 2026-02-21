# CNC24 Apple Touch Icons Pack

This package was generated from:

- `https://www.cnc24.com/wp-content/uploads/2022/04/cnc24-logo.svg`
- black background

## What is included

### Root icon files

Location: `dist/root-icons/`

- `apple-touch-icon.png`
- `apple-touch-icon-precomposed.png`
- `apple-touch-icon-57x57.png`
- `apple-touch-icon-57x57-precomposed.png`
- `apple-touch-icon-60x60.png`
- `apple-touch-icon-60x60-precomposed.png`
- `apple-touch-icon-72x72.png`
- `apple-touch-icon-72x72-precomposed.png`
- `apple-touch-icon-76x76.png`
- `apple-touch-icon-76x76-precomposed.png`
- `apple-touch-icon-114x114.png`
- `apple-touch-icon-114x114-precomposed.png`
- `apple-touch-icon-120x120.png`
- `apple-touch-icon-120x120-precomposed.png`
- `apple-touch-icon-144x144.png`
- `apple-touch-icon-144x144-precomposed.png`
- `apple-touch-icon-152x152.png`
- `apple-touch-icon-152x152-precomposed.png`
- `apple-touch-icon-167x167.png`
- `apple-touch-icon-167x167-precomposed.png`
- `apple-touch-icon-180x180.png`
- `apple-touch-icon-180x180-precomposed.png`

### WordPress plugin

Location: `wp-plugin/cnc24-apple-touch-icons/`

- `cnc24-apple-touch-icons.php`
- `icons/*.png` (same files as above)

The plugin serves requests like:

- `/apple-touch-icon.png`
- `/apple-touch-icon-precomposed.png`
- `/apple-touch-icon-120x120.png`
- `/apple-touch-icon-120x120-precomposed.png`

## Recommended setup (simplest in WordPress)

1. Zip `wp-plugin/cnc24-apple-touch-icons/`.
2. In WordPress admin: `Plugins` -> `Add New` -> `Upload Plugin`.
3. Upload zip and activate.
4. Test:
   - `https://your-domain.com/apple-touch-icon.png`
   - `https://your-domain.com/apple-touch-icon-120x120-precomposed.png`

If those URLs return `200` and an image, your 404 issue is fixed.

## Alternative setup (no plugin)

Upload all files from `dist/root-icons/` to your WordPress web root so they are directly available at:

- `https://your-domain.com/apple-touch-icon.png`
- `https://your-domain.com/apple-touch-icon-120x120.png`
- etc.

## Regenerate later

Use:

```bash
scripts/generate-icons.sh /path/to/logo.svg dist/root-icons
```

Optional scale:

```bash
LOGO_SCALE_PERCENT=84 scripts/generate-icons.sh /path/to/logo.svg dist/root-icons
```
