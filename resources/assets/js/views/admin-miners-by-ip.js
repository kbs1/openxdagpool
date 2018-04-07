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
		$('#ipAddressDetailsModal input').val($(this).text());
		$('#ipAddressDetailsModal textarea').val($.trim($('.ip-miners', $(this).closest('tr')).data('tooltip').replace(/^\s+/gm, '')));
		$('#ipAddressDetailsModal').addClass('is-active');

		return false;
	}

	module.exports = View;
})(this);
