"use strict";

(function(root)
{
	var View = function()
	{
		if (!(this instanceof View))
			throw Error('View must be instanitated with `new`');

		this.ajax = require('ajax-request');
		this.loading = false;

		this.registerHandlers();
	}

	View.prototype.registerHandlers = function()
	{
		$(document).ready(this.loadPoolStats.bind(this));
		window.setInterval(this.loadPoolStats.bind(this), 30000);
	}

	View.prototype.loadPoolStats = function()
	{
		if (this.loading)
			return;

		this.loading = true;

		var request = {
			'url': '/api/pool/stats',
			'method': 'GET',
			'data': {},
			'encoding': 'utf-8',
			'headers': {
				'Accept': 'application/json',
			},
		};

		var self = this;

		this.ajax(request, function(error, response, body) {
			if (error)
				return self.unableToLoadStats();

			if (!response)
				return self.unableToLoadStats();

			if (response.headers['content-type'] !== 'application/json')
				return self.unableToLoadStats();

			var json;
			try {
				json = JSON.parse(body);
			} catch (error) {
				return self.unableToLoadStats();
			}

			if (!json.hashrate || !json.miners || !json.fees || !json.config || !json.uptime || !json.uptime_exact)
				return self.unableToLoadStats();

			$('.home-view .stats .stat').each(function(index, el) {
				$(this).removeClass('is-loading').text(json[$(this).data('stat')]);
			});

			$('.home-view .stats .stat-tooltip').each(function(index, el) {
				$(this).addClass('tooltip').attr('data-tooltip', json[$(this).data('stat')]);
			});
		});

		this.loading = false;
	}

	View.prototype.unableToLoadStats = function()
	{
		$('.home-view .stats .stat').removeClass('is-loading').text('?');
	}

	module.exports = View;
})(this);
