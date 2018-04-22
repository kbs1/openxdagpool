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
		$('.found-block-details').click(this.foundBlockDetails);
	}

	View.prototype.foundBlockDetails = function()
	{
		$('#foundBlockDetailsModal input[name=found_at]').val($(this).data('foundAt'));
		$('#foundBlockDetailsModal input[name=hash]').val($(this).data('tooltip'));
		$('#foundBlockDetailsModal input[name=t]').val($(this).data('t'));
		$('#foundBlockDetailsModal input[name=res]').val($(this).data('res'));
		$('#foundBlockDetailsModal a.button.is-primary').href('https://explorer.xdag.io' + '/block/' + $(this).data('res'));

		$('#foundBlockDetailsModal').addClass('is-active');

		return false;
	}

	module.exports = View;
})(this);
