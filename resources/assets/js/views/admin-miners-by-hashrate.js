"use strict";

(function(root)
{
	var View = function()
	{
		if (!(this instanceof View))
			throw Error('View must be instanitated with `new`');

		this.registerHandlers();
	}

	View.prototype.registerHandlers = function()
	{
		$('.miner-details').click(this.minerDetails);
	}

	View.prototype.minerDetails = function()
	{
		$('#minerDetailsModal input[name=address]').val($(this).text());
		$('#minerDetailsModal input[name=unpaid_shares]').val($(this).data('unpaidShares'));
		$('#minerDetailsModal input[name=in_out_bytes]').val($(this).data('inOutBytes'));
		$('#minerDetailsModal textarea').val($('.ips-and-port', $(this).closest('tr')).data('tooltip'));
		$('#minerDetailsModal').addClass('is-active');

		return false;
	}

	module.exports = View;
})(this);
