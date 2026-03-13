<?php

/*
|--------------------------------------------------------------------------
| Theme Constants
|--------------------------------------------------------------------------
|
| Global constants available throughout the theme. This file is loaded
| first in functions.php, so constants defined here can be used in
| setup.php, filters.php, and all other theme files.
|
*/

// Reads the text domain from style.css — change it there when starting a new project.
define('THEME_TEXTDOMAIN', wp_get_theme()->get('TextDomain'));
