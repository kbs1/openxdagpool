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
		$(document).ready(this.initDatePickers);
	}

	View.prototype.initDatePickers = function()
	{
		$('#important_message_until, #pool_created_at').datepicker({
			dateFormat: 'yy-mm-dd'
		});
	}

	module.exports = View;
})(this);
