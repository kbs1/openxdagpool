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
		$('.ip-address-details').click(this.ipAddressDetails);
	}

	View.prototype.ipAddressDetails = function()
	{
		$('#ipAddressDetailsModal input[name=ip_address]').val($(this).text());
		$('#ipAddressDetailsModal input[name=unpaid_shares]').val($(this).data('unpaidShares'));
		$('#ipAddressDetailsModal input[name=in_out_bytes]').val($(this).data('inOutBytes'));
		$('#ipAddressDetailsModal textarea').val($.trim($('.ip-miners', $(this).closest('tr')).data('tooltip').replace(/^\s+/gm, '')));
		$('#ipAddressDetailsModal').addClass('is-active');

		return false;
	}

	module.exports = View;
})(this);
