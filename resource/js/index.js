(function(_, callback, undefined)
{

	callback(

		// Dom
		{
			header:  $('header'),
			footer:  $('footer'),
			section: $('section'),
			jumbotron: $('.jumbotron')
		},

		// Action
		{

			// 彩虹
			rainbow: function( element, colors ){

				return function( words, result ){

					$.each( colors, function( i, color ){

						if( words[i] ){

							result += '<bdo style="color: #' + color + ';">' + words[ i ] + '</bdo>';

						}

					});

					return element.html( result );

				}( element.text().split(''), '' );

			}

		}

	);

})(window, function( dom, action )
{

	// 选项卡切换
	/*
	$.tab({
		menus: dom.section.find('dt > span'),
		targets: dom.section.find('dd > div')
	});
	*/

	/*
	$.dragSort({
		// from
		caster: dom.section.find('ul'),
		// to
		sucker: dom.section.find('ul'),
		// 距离
		distance: 30,
		// item
		items: dom.section.find('li'),
		// 开始
		onStart: function( item, e ){},
		// 结束
		onEnd: function( item, e ){},
	});
	*/

	// 彩虹
	// action.rainbow( dom.jumbotron.find('small'),  /* '09e 0cf 0c6 f00 f60 f93 fc0 333 fff fff fff' */'09e 0cf f30 f60 f93'.split(' ') );

});