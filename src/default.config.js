const pkg = require("./package.json");
const themeName = process.env.NAME || pkg.name || "ioptheme";

module.exports = {
  src: `./wp-content/themes/${themeName}/src`,
  dist: `./wp-content/themes/${themeName}/dist`,
  entry: [
    "./js/main.js",
    "./js/admin.js",
    "./js/editor.js",
    "./sass/main.scss"
  ],
  publicPath: `/wp-content/themes/${themeName}/dist/`,

  // enable polling on Windows (no iNotify events), does `isWindows` work?
  // This property can be a boolean or an integer > 250
  // NOTE: iNotify events sometimes stop working on macOS, restart Docker to get them back
  usePolling: false

  // OPTIONAL: Specify a Sass implementation, accepts `node-sass` (default) and `sass` (Dart Sass)
  sass: 'node-sass',

  // TODO: This should be automatic, maybe valid entries are: true, false, null, string (a valid url)?
  proxy:
    "http://devserver-proxy-token--d939bef2a41c4aa154ddb8db903ce19fff338b61"
};
