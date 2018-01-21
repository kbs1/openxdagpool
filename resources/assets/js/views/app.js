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
		$('#footer').click(this.showContactUsModal);
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

	View.prototype.showContactUsModal = function()
	{
		var email = 'gjddm5a@QCzOdmmx.Ym0';
		var key = '6ZiA9a3McHgCBUmDFjlJS81PyzvbYeO4np0hErx2qwfTGkLtRXWKsdVNo5QuI7';
		var shift = email.length;
		var result = '';

		for (var i = 0; i < email.length; i++) {
			if (key.indexOf(email.charAt(i)) == -1)
				result += email.charAt(i);
			else
				result += key.charAt((key.indexOf(email.charAt(i)) - shift + key.length) % key.length);
		}

		$('#contactEmail').html('<a href="mailto:' + result + '" target="_blank">' + result + '</a>');
		$('#contactUsModal').addClass('is-active');

		return false;
	}

	module.exports = View;
})(this);
