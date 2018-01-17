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
		$('.navbar-burger').click(this.handleMobileMenu);
		$('.notification .delete').click(this.handleNotifications);
		$('.close-modal').click(this.closeModal);
	}

	View.prototype.handleMobileMenu = function()
	{
		$('#navMenu').toggleClass('is-active');
	}

	View.prototype.handleNotifications = function()
	{
		$(this).parent().remove();
	}

	View.prototype.closeModal = function()
	{
		$(this).closest('.modal').removeClass('is-active');
		return false;
	}

	module.exports = View;
})(this);
