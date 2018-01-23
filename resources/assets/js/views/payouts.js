"use strict";

(function(root)
{
	var View = function(json)
	{
		if (!(this instanceof View))
			throw Error('View must be instanitated with `new`');

		this.json = json;
		this.registerHandlers();
	}

	View.prototype.registerHandlers = function()
	{
		$(document).ready(this.initGraph.bind(this));
	}

	View.prototype.initGraph = function()
	{
		c3.generate({
			bindto: '#graph',
			data: {
				json: this.json,
				x: 'x',
				xFormat: '%Y-%m-%d'
			},
			axis: {
				x: {
					type: 'timeseries',
					tick: {
						format: '%Y-%m-%d'
					}
				}
			}
		});
	}

	module.exports = View;
})(this);
