try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {}

window.appView = require('./views/app');
window.homeView = require('./views/home');
window.minersView = require('./views/miners');
window.statsView = require('./views/stats');

window.c3 = require('c3');
window.d3 = require("d3");

// require('./bulma-extensions');
