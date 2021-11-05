# wp-customizer-demo

Proof of concepts for pre-populating / overriding / disabling the customizer in WordPress child themes.

## 1. flash-child

Demo child theme for [Themegrill - *Flash* MultiPurpose WordPress Theme](https://themegrill.com/themes/flash/).

### Problem

I pitched for a job where the client wanted to override the theming options provided by the *Flash* theme.

> change the hero font and color to something that reads better
> ...
> No coding should be required. The deliverable I am looking is a theme zip file that can be installed in any wordpress site, and the CSS changes/additions on a separate file.

In *Flash* as in WordPress, colours and fonts are specified using the WordPress customizer: *Appearance > Customise*.

It sounded like the client wanted to set these values without using the customiser, so that the changes could easily be replicated to *any wordpress site*.

### Solutions

The WordPress Customizer is helpful for:

* clients without coding knowledge
* clients who want to set up 'unique' themes quickly

The WordPress Customizer is unhelpful for:

* clients who will refactor their parent theme customisations into a child theme one day (see: [Make WordPress Core - Child theme does not inherit previous customizer settings from parent theme](https://core.trac.wordpress.org/ticket/27177))
* clients who want to avoid repetition when setting up clone sites

I thought of several solutions to this:

1. Ignore whatever value is set in the customizer by overriding the output in a child theme
   * makes the customizer live preview inaccurate
   * could be confusing for authors
2. Remove the customizer options and hardcode the preferred settings in a child theme's stylesheet
   * requires WET code to duplicate the dynamic inline CSS from the parent theme to the child theme
   * removes functionality that might be useful in some cases
3. Retain the customizer options but override the defaults with your own in a child theme
   * customise existing functionality rather than overriding it
   * CSS Custom Properties could be used to expose these defaults to the child theme CSS to keep these DRY
4. Use a [WordPress Plugins - Customizer Export/Import plugin](https://wordpress.org/plugins/customizer-export-import/) to set your preferred customizer settings then export these to a file
   * file could be committed to version control
   * quickly load your preferences when setting up a site
   * still be a manual process.
5. Implement one of the above options in a plugin instead, if a child theme is already in use
6. Change to a different theme/framework that is less opinionated about how theming is managed
7. Change the customizer colour palette to one of your own (this doesn't address the font issue):
   * [Intelliwolf - How To Change The Default Colors In WordPress Customizer](https://www.intelliwolf.com/change-default-colors-in-wordpress-customizer/)
   * [Github - Automattic Iris Color Picker](http://automattic.github.io/Iris/) - initialised in `wp-admin/js/color-picker.js`
   * [BeaverBuilder - Add color presets to Customizer](https://docs.wpbeaverbuilder.com/bb-theme/defaults-for-styles/colors/add-color-presets-to-customizer/) - re-initialise Iris

### WP_Customize_Manager

* [WordPress Code Reference - WP_Customize_Manager class](https://developer.wordpress.org/reference/classes/wp_customize_manager/)

### Kirki

*Flash*  uses the [Kirki](https://kirki.org/) WordPress Customizer Framework. This is a wrapper for WP_Customize_Manager.

The [documentation for the theme is non-technical](https://docs.themegrill.com/flash/). You need to go dig into the PHP files to see what's possible.

* [Github - Kirki repository](https://github.com/kirki-framework)
* [Ralph J. Smit - Beginners guide for the Kirki customizer framework](https://ralphjsmit.com/how-to-get-started-with-the-kirki-customizer-framework/)
* [Ralph J. Smit - How to use the Kirki typography control: custom fonts & Google](https://ralphjsmit.com/kirki-typography/)
* [Kirki - Default value in get_theme_mod function](https://github.com/kirki-framework/kirki/issues/73)
* [Kirki - Removing fields via a child theme](https://github.com/kirki-framework/kirki/issues/1308)
* [Kirki - Missing remove_panel, remove_section, remove_field](https://github.com/kirki-framework/kirki/issues/1609)

### Known Issues

See `TODO`s in files.
