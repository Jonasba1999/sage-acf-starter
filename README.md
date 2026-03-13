# sage-acf-starter

A [Sage 11](https://roots.io/sage/) starter theme with an opinionated ACF block development setup using Blade templates and View Composers.

Built on top of Sage — Laravel Blade templating, Vite, Tailwind CSS, and [Acorn](https://github.com/roots/acorn).

## What this adds on top of Sage

### ACF block scaffolding command

Generate a complete block scaffold with a single command:

```bash
wp acorn make:block BlockName
```

This creates:
- `app/Blocks/BlockName/block.json` — block registration with ACF config
- `app/Blocks/BlockName/preview.jpg` — placeholder preview image
- `resources/views/blocks/block-name.blade.php` — Blade template
- `app/View/Composers/BlockBlockName.php` — View Composer
- `acf-json/group_block_block_name.json` — ACF field group (edit fields in ACF admin)

### ACF blocks rendered with Blade

Blocks use `renderCallback` in `block.json` to route rendering through Acorn's Blade engine. Full support for Blade directives, layouts, and components inside blocks.

### View Composers for blocks

Each block gets its own Composer class in `app/View/Composers/` for fetching and transforming ACF field data before it reaches the template.

### Block anchor support

Generated block templates include anchor support out of the box:

```html
<section @if (!empty($block['anchor'])) id="{{ $block['anchor'] }}" @endif ...>
```

### Block preview images

When a block is in preview mode in the editor inserter, it renders a `preview.jpg` instead of the live block output.

### Admin hardening

- Removes Posts and Comments from the admin menu
- Disables the Customizer and Site Editor (URL access blocked)
- Removes Customize and Comments from the admin bar
- Hides ACF field groups in production (`WP_ENV === 'production'`)
- Disables block directory suggestions in the editor
- Removes the default "Hello World" post on theme activation

### Frontend cleanup

- Removes `wp-block-library` and `wp-block-library-theme` CSS
- Removes emoji detection scripts and styles

### Theme constants

`app/defines.php` is loaded before all other theme files. The text domain reads from `style.css` — change it once there and it propagates everywhere:

```php
define('THEME_TEXTDOMAIN', wp_get_theme()->get('TextDomain'));
```

## Starting a new project

1. Clone the repo
2. Update `style.css` — set `Theme Name`, `Text Domain`, and other headers
3. Run `composer install && npm install`
4. Start building blocks with `wp acorn make:block BlockName`

---

## Sage

**Advanced hybrid WordPress starter theme with Laravel Blade and Tailwind CSS**

- Clean, efficient theme templating with Laravel Blade
- Modern front-end development workflow powered by Vite
- Out of the box support for Tailwind CSS
- Harness the power of Laravel with [Acorn integration](https://github.com/roots/acorn)

[Read the Sage docs](https://roots.io/sage/docs/installation/)
