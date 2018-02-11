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
		$('#footer').click(this.showContactUsModal.bind(this));
		$('.close-modal').click(this.closeModal);
	}

	View.prototype.handleMobileMenu = function()
	{
		$('#navMenu').toggleClass('is-active');
	}

	View.prototype.handleNotifications = function()
	{
		$(this).parent().hide();
	}

	View.prototype.closeModal = function()
	{
		$(this).closest('.modal').removeClass('is-active');
		return false;
	}

	View.prototype.showContactUsModal = function()
	{
		var el = $('#contactEmail');

		if (!$(el).data('transformApplied')) {
			var email = new Buffer($(el).text(), 'base64').toString('ascii');
			email = email.replace(/[a-zA-Z]/g,function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);}); //rot13
			email = email.replace(/&/g, '@').replace(/\*/g, '.');
			$('#contactEmail').html('<a href="mailto:' + email + '" target="_blank">' + email + '</a>').data('transformApplied', true);
		}

		$('#contactUsModal').addClass('is-active');

		return false;
	}

	module.exports = View;
})(this);
