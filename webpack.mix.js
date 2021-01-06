let mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.disableNotifications();

mix.js("modules/media/resources/assets/js/media.js", "public/modules/media/js");
mix.js(
  "modules/mediaX/resources/assets/js/mediaX.js",
  "public/modules/mediaX/js"
);
mix.js(
  "modules/display/resources/assets/js/display.js",
  "public/modules/display/js"
);
mix.js(
  "modules/displayProfile/resources/assets/js/displayProfile.js",
  "public/modules/displayProfile/js"
);
mix.js(
  "modules/layout/resources/assets/js/layout.js",
  "public/modules/layout/js"
);
mix.js(
  "modules/schedule/resources/assets/js/schedule.js",
  "public/modules/schedule/js"
);
mix.js(
  "modules/campaign/resources/assets/js/campaign.js",
  "public/modules/campaign/js"
);
mix.js(
  "modules/displaygroup/resources/assets/js/displayGroup.js",
  "public/modules/displayGroup/js"
);
mix.js(
  "modules/dayparting/resources/assets/js/dayparting.js",
  "public/modules/dayparting/js"
);
