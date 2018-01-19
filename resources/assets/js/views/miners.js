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
		$(document).ready(this.loadMinersData.bind(this));
		window.setInterval(this.loadMinersData.bind(this), 30000);

		$('#addMiner').click(this.addMiner);
		$('.delete-miner').click(this.deleteMiner);
	}

	View.prototype.loadMinersData = function()
	{
		if (this.loading)
			return;

		this.loading = true;

		var data = [];

		$('.miners-view .miners-list tr.miner').each(function() {
			data.push($(this).data('uuid'));
		});

		var request = {
			'url': '/api/miners',
			'method': 'POST',
			'data': {'_token': $('head meta[name=csrf-token]').attr('content'), 'uuid': data},
			'encoding': 'utf-8',
			'headers': {
				'Accept': 'application/json',
			},
		};

		var self = this;

		this.ajax(request, function(error, response, body) {
			self.loading = false;

			if (error)
				return self.unableToLoadMinersData();

			if (!response)
				return self.unableToLoadMinersData();

			if (response.headers['content-type'] !== 'application/json')
				return self.unableToLoadMinersData();

			var json;
			try {
				json = JSON.parse(body);
			} catch (error) {
				return self.unableToLoadMinersData();
			}

			for (var uuid in json) {
				var miner = json[uuid];
				var tr = $('.miners-view .miners-list tr.miner[data-uuid=' + uuid + ']');

				if (!tr.length) continue;

				$('.api', tr).removeClass('is-loading');

				$('.miner-status', tr).text(miner.status);
				$('.miner-hashrate', tr).text(miner.hashrate);
				$('.miner-nopaid-shares', tr).text(miner.nopaid_shares);
				$('.miner-balance', tr).text(miner.balance);

				if (miner.ip_and_port) {
					$('.miner-address', tr).addClass('tooltip').attr('data-tooltip', 'IP and port: ' + miner.ip_and_port + ($(tr).data('note') ? ', Note: ' + $(tr).data('note') : ''));
				}
			}
		});
	}

	View.prototype.unableToLoadMinersData = function()
	{
		$('.miners-view .miners-list .api').removeClass('is-loading').text('?');
	}

	View.prototype.addMiner = function()
	{
		$('#addMinerModal').addClass('is-active');
	}

	View.prototype.deleteMiner = function()
	{
		var miner = $(this).closest('tr');
		$('#deleteMinerAddress').val($(miner).data('address'))
		$('#deleteMinerNote').val($(miner).data('note'))
		$('#deleteMinerModal').addClass('is-active');

		return false;
	}

	module.exports = View;
})(this);
