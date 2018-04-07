try {
    window.$ = window.jQuery = require('jquery');
    require('jquery-ui/ui/widgets/datepicker');
} catch (e) {}

window.appView = require('./views/app');
window.homeView = require('./views/home');
window.minersView = require('./views/miners');
window.statsView = require('./views/stats');
window.payoutsView = require('./views/payouts');
window.hashrateView = require('./views/hashrate');
window.adminSettingsView = require('./views/admin-settings');
window.adminMinersByIpView = require('./views/admin-miners-by-ip');
window.adminMinersByHashrateView = require('./views/admin-miners-by-hashrate');

window.d3 = require('d3');
window.c3 = require('c3');
