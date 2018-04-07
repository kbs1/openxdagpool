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
		$('#minerDetailsModal input').val($(this).text());
		$('#minerDetailsModal textarea').val($('.ips-and-port', $(this).closest('tr')).data('tooltip'));
		$('#minerDetailsModal').addClass('is-active');

		return false;
	}

	module.exports = View;
})(this);
