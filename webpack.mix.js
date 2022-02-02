const mix = require("laravel-mix");
var publicPath = "../../../public";
if (mix == 'undefined') {
    const { mix } = require("laravel-mix");
}
mix.js(__dirname + "/src/Resources/assets/admin/js/app.js", "src/Resources/assets/dist/js/").vue()
    .sass(__dirname + "/src/Resources/assets/admin/scss/menu.scss", "src/Resources/assets/dist/css/menu.css")
    .sass(__dirname + "/src/Resources/assets/admin/scss/admin.media.scss", "src/Resources/assets/dist/css/admin.media.css")
    .sass(__dirname + "/src/Resources/assets/admin/scss/admin.media-modal-view.scss", "src/Resources/assets/dist/css/admin.media-modal-view.css")
    .copyDirectory(__dirname + "/src/Resources/assets/dist/", publicPath + "/media-manager/");