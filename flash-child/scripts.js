/**
 * Flash Child theme
 */

jQuery(document).ready(($) => {
    const demo_id = 'flash-child-css-variables-demo';

    $('body').prepend(`<div id="${demo_id}"><h2>Flash Child CSS Variables</h2></div>`);

    $.each(flash_child_color_scheme, function (key, value) {
        let key_formatted = '--flash-' + key.replaceAll('_', '-'); // replaceAll not supported by IE11

        $(`#${demo_id}`).append(`<div style="color: var(${key_formatted})">${key_formatted}: ${value}</div>`);
    });
});
