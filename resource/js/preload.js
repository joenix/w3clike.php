(function(_, callback, undefined){

	function log( obj ){
		return function( txt ){
			for( var i in obj ){
				txt += i + ': ' + obj[i] + '\n';
			}
			return txt;
		}('');
	}

	// Project Root
	_.project = _.project || '',

	// Active Doc
	_.active  = _.active ? ( '/js/' + _.active + '.js' ) : '';

	var root = function( loc, project ){
		return loc.protocol + '//' + ( loc.host || (loc.hostname + ':' + loc.port) ) + project;
	}( _.location, _.project );

	callback(

		// Constant
		{
			root: root + '/resource'
		},

		// Action
		(function( root ){

			var

			// Document
			document = _.document,

			// Head
			head = document.querySelector ? document.querySelector('head') : document.getElementsByTagName('head')[0],

			// Body
			body = document.body,

			// Noop
			noop = function(){};

			return {

				// Merge Data
				merge: function( data ){

					return _.active ? ( data.push( _.active ), data ) : data;

				},

				// Read Resource
				read: function( url, callback ){

					callback = callback || noop;

					return function( type ){

						var item = undefined;

						switch( type ){

							// Json
							case 'json':

								item = _.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');

								item.onreadystatechange = function(){

									if( item.readyState == 4 && item.status == 200 ){
										callback( eval('(' + item.responseText + ')') );
									}
								};

								item.open('GET', url, true);
								item.send();

								break;

							// Image
							case 'img':

								item = new Image();
								item.src = url;

								item.complete ? callback( item ) : ( item.onload = function(){
									callback( item ), item.onload = null;
								});

								break;

							// Javascript
							case 'js':

								item = document.createElement('script');
								item.type = 'text/javascript',
								item.language = 'javascript',
								item.defer = false,
								item.src = url;

								item.onload = item.onreadystatechange = function(){
									if( item.ready ){
										return false;
									}
									if( !item.readyState || item.readyState == 'loaded' || item.readyState == 'complete' ){
										item.ready = true, callback( item );
									}
								}

								body.appendChild( item );

								break;

							// Stylesheet
							case 'css':

								item = document.createElement('link');
								item.type = 'text/css',
								item.rel = 'stylesheet',
								item.href = url;

								head.appendChild( item );

								callback( item );

								break;

							default: console.log('unknow type: ' + type);
						}

						// callback( item );

					}( function( type ){

						return ~['jpg','jpeg','gif','png','bmp'].join(' ').indexOf(type) ? 'img' : type;

					}( /\w+$/.exec( url ).toString().toLowerCase() ) );
				},

				// Recursive
				recursive: function( arr, callback ){
					!function( e ){
						if( arr.length ){
							callback( arr.shift(), function(){ return e( arr, callback ) });
						}
					}( arguments.callee );
				},

				// Process
				process: function( len, den ){
					return Math.floor( (den - len) / den * 100 );
				},

				// Destory
				destory: function(){
					_.process = undefined, _.ready = undefined;
				},

				// On Process
				onProcess: function( percent ){
					if( _.process ){
						_.process( percent );
					}
				},

				// On Complete
				onReady: function( destory ){
					if( _.ready ){
						_.ready();
					}
					destory();
				}
			}

		})( root )
	);

})(window, function( constant, action ){

	var numerator, denominator;

	// Load Manifest
	action.read( constant.root + '/config/' + document.querySelector('script[main]').getAttribute('main') + '.json', function( data ){

		// Merge Active
		data = action.merge( data );

		// The Denominator
		denominator = data.length;

		// Recursive Resource
		action.recursive( data, function( item, callback ){

			// The Numerator
			numerator = data.length;

			// On Process
			action.onProcess( action.process( denominator - numerator, denominator ) );

			// Read Resource
			action.read( constant.root + item, function(){

				// Recursive Callback
				callback();

				// On Ready
				if( !numerator ){
					action.onReady( action.destory );
				}

			});

		});

	});

});