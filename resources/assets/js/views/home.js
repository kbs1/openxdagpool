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

		$('.stat-tabs li').click(this.handleTabs);
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
			self.loading = false;

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

			if (!json.pool_hashrate || !json.network_hashrate || !json.difficulty || !json.difficulty_exact || !json.supply || !json.blocks || !json.main_blocks
				|| !json.miners || !json.fees || !json.config || !json.uptime || !json.uptime_exact)
				return self.unableToLoadStats();

			$('.home-view .stats .stat').each(function(index, el) {
				$(this).removeClass('is-loading').text(json[$(this).data('stat')]);
			});

			$('.home-view .stats .stat-tooltip').each(function(index, el) {
				var prefix = $(this).data('stat-prefix') ? $(this).data('stat-prefix') : '';
				$(this).addClass('tooltip').attr('data-tooltip', prefix + json[$(this).data('stat')]);
			});
		});
	}

	View.prototype.unableToLoadStats = function()
	{
		$('.home-view .stats .stat').removeClass('is-loading').text('?');
	}

	View.prototype.handleTabs = function()
	{
		$('li', $(this).closest('ul')).removeClass('is-active');
		$(this).addClass('is-active');
		$('.stats nav').hide();
		$('.stats ' + $(this).data('target')).css('display', 'flex');
	}

	module.exports = View;
})(this);
