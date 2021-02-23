import localforage from 'localforage';

// to enable router uncomment this line
// import VueRouter from 'vue-router';

window.debounce = require('lodash.debounce');

window.UserSettings = localforage.createInstance({
	driver: localforage.INDEXEDDB,
	name: 'imre_properties',
	storeName: 'Settings'
});

// init router and define routes here
if (typeof VueRouter != 'undefined') {
	window.router = new VueRouter({
		routes: [
			{
				name: 'offering-type',
				path: '/for/:val' // i.e. this will listen this route #/for/lease
			}
		]
	});
}

window.eventHub = new Vue();

Vue.component('Pagination', require('./components/pagination.vue').default);
// Vue.component('MapView', require('./components/map.vue').default);

import Vue2Filters from 'vue2-filters'

Vue.use(Vue2Filters);

// filters
Vue.component(
	'FilterSelect',
	require('./components/filter-select.vue').default
);

Vue.filter('getMinMaxSurface', function(val) {
	var spaces = [];
	val.forEach(function(item) {
		spaces.push(item.space_surface_value);
	});

	var minRaw = Math.min.apply(null, spaces),
		maxRaw = Math.max.apply(null, spaces);

	var min = new Intl.NumberFormat('en-US', {
		style: 'decimal'
	}).format(minRaw.toString());

	var max = new Intl.NumberFormat('en-US', {
		style: 'decimal'
	}).format(maxRaw.toString());

	if (min == 0 && max == 0) {
		return 'On request';
	} else if (min == 0) {
		return 'Up to ' + max + ' SF';
	} else if (min == max) {
		return min + ' SF';
	} else {
		return min + ' – ' + max + ' SF';
	}
});

Vue.filter('numberFormat', function(string) {
	return new Intl.NumberFormat('en-US', {
		style: 'decimal'
	}).format(string.toString());
});

Vue.filter('numberFormatSize', function(string) {
	if (string == 0) {
		return 'On request';
	} else {
		return new Intl.NumberFormat('en-US', {
			style: 'decimal'
		}).format(string.toString());
	}
});

var Properties = new Vue({
	// uncomment if using router
	// router,
	el: '#imre-properties',
	data: {
		layout: 'grid',
		loading: true,
		advanced_search: false,
		active_search_params: [],
		messages: {
			empty: 'Sorry, we found no properties matching your filters.',
			loading: 'Loading, please wait ...'
		},
		sizes: [
			{ id: '0-2500', name: '0 - 2,500 SF' },
			{ id: '2500-5000', name: '2,500 - 5,000 SF' },
			{ id: '5000-10000', name: '5,000 - 10,000 SF' },
			{ id: '10000-20000', name: '10,000 - 20,000 SF' },
			{ id: '20000-50000', name: '20,000 - 50,000 SF' },
			{ id: '50000', name: '50,000+ SF' }
		],
		units: [
			{ id: '1-10', name: '1-10' },
			{ id: '11-20', name: '11-20' },
			{ id: '21-30', name: '21-30' },
			{ id: '31-40', name: '31-40' },
			{ id: '41-50', name: '41-50' },
			{ id: '51-60', name: '51-60' },
			{ id: '61-70', name: '61-70' },
			{ id: '71-80', name: '71-80' },
			{ id: '81-90', name: '81-90' },
			{ id: '91-100', name: '91-100' },
			{ id: '101', name: '101+' }
		],
		prices: [
			{ id: '0-250000', name: '0 - $250,000' },
			{ id: '250000-500000', name: '$250,000 - $500,000' },
			{ id: '500000-1000000', name: '$500,000 - $1,000,000' },
			{ id: '1000000-5000000', name: '$1,000,000 - $5,000,000' },
			{ id: '5000000-10000000', name: '$5,000,000 - $10,000,000' },
			{ id: '10000000-50000000', name: '$10,000,000 - $50,000,000' },
			{ id: '50000000', name: '$50,000,000+' }
		],
		statuses: [
			{ id: 'active', name: 'Active' },
			{ id: 'escrow', name: 'Escrow' },
			{ id: 'closed', name: 'Closed' }
		],
		//
		properties: [],
		filtered_properties: [],
		filtered_agents: [],
		agents: [],
		terms: {},
		cities: [],
		neighborhoods: [],
		closed_properties: [],
		showReset: false,
		propertiesCount: 0,

		// set gmaps api is not loaded
		gmaps: false,

		// pagination
		
		paginated_properties: [],
		per_page: 21,
		page: 1
		
	},
	computed: {
		status: function() {
			return this.loading ? this.messages.loading : this.messages.empty;
		}
	},
	created: function() {
		var vm = this;

		// listen for click outside show_location_filter
		document.addEventListener('mouseup', function(e) {
			if (vm.show_location_filter) {
				var el = event.target;
				var inside = false;
				while (el) {
					if (el == document.querySelector('.location-container')) {
						inside = true;
					}
					el = el.parentNode;
				}

				// close location filter if click outside
				if (inside === false) {
					vm.show_location_filter = false;
				}
			}
		});
	},
	mounted: function() {
		var vm = this;

		// this.initMap();

		// set layout from settings
		UserSettings.getItem('layout').then(val => {
			if (val) {
				vm.layout = val;
			} else {
				UserSettings.setItem('layout', vm.layout);
			}

			// emit layout set
			eventHub.$emit('layoutSet');
		});

		// listen for search event
		eventHub.$on('search', function() {
			UserSettings.setItem('search', vm.active_search_params);
		});

		// listen for search event
		eventHub.$on('goToPage', function(page) {
			vm.page = page;
			vm.paginate();
		});

		// listen for layout set
		// eventHub.$on('layoutSet', function() {
		// 	document.querySelector('.site-footer').style.display =
		// 		vm.layout == 'map' ? 'none' : 'block';
		// });
		// listen for search event
		eventHub.$on('mapBoundsChanged', function(bounds) {
			vm.map_bounds = bounds;
			vm.search();
		});

		// fetch
		this.fetch();

		var vm = this;
		setTimeout(function(){
			vm.propertiesCount = document.querySelectorAll('.property-card').length;
			vm.properties.sort(vm.sortPropertiesByPrice);
		}, 1000);

	},
	watch: {
		filtered_properties: function() {
			var vm = this;

			// if cities is not active, adjust
			if (!this.isActiveParam('city')) {
				this.cities = [];
			}

			// if neighborhoods is not active, adjust
			if (!this.isActiveParam('neighborhood')) {
				this.neighborhoods = [];
			}

			// if agents is not active, adjust
			if (!this.isActiveParam('agents')) {
				this.filtered_agents = [];
			}

			// loop properties and create markers
			for (var i = 0; i < this.filtered_properties.length; i++) {
				var property = this.filtered_properties[i];

				// city
				if (!this.isActiveParam('city') && property.location.city) {
					if (this.cities.indexOf(property.location.city) == -1) {
						this.cities.push(property.location.city);
					}
				}

				// neighborhood
				if (
					!this.isActiveParam('neighborhood') &&
					property.location.neighborhood
				) {
					if (
						this.neighborhoods.indexOf(
							property.location.neighborhood
						) == -1
					) {
						this.neighborhoods.push(property.location.neighborhood);
					}
				}

				// agents
				if (
					this.active_search_params.indexOf('agents') == -1 &&
					property.brokers
				) {
					property.brokers.map(id => {
						vm.filtered_agents.push(id);
					});
				}

				//Remove closed properties from filtered_properties,
				//Add them to their own Array
				if( this.filtered_properties[i].status === 'closed' ) {
					var filteredSplice = this.filtered_properties.splice(i,1);

					filteredSplice.map((property) => this.closed_properties.push(property))
				}


			}

			this.paginate();
		},

		layout: function() {
			var vm = this;
			// reset pagination on layout change
			this.$nextTick(function() {
				if (vm.layout != 'map') {
					vm.page = 1;
					vm.paginate();
				}
				UserSettings.setItem('layout', vm.layout);
				eventHub.$emit('layoutSet');
			});
		}
	},
	filters: {
		cleanTags: function(val) {
			return jQuery('<br />')
				.html(val)
				.text();
		},
		getMinMaxPrice: function(val) {
			var spaces = [];
			val.forEach(function(item) {
				spaces.push(item.space_price_value);
			});

			var minRaw = Math.min.apply(null, spaces),
				maxRaw = Math.max.apply(null, spaces);

			var min = new Intl.NumberFormat('en-US', {
				style: 'decimal'
			}).format(minRaw.toString());

			var max = new Intl.NumberFormat('en-US', {
				style: 'decimal'
			}).format(maxRaw.toString());

			if (min == 0 && max == 0) {
				return 'On request';
			} else if (min == max) {
				return '$' + min;
			} else {
				return '$' + min + ' – ' + '$' + max;
			}
		},

		onRequestPrice: function(val) {
			if (val == 0) {
				return 'On request';
			} else {
				var formattedVal = new Intl.NumberFormat('en-US', {
					style: 'decimal'
				}).format(val.toString());
				return '$' + formattedVal;
			}
		},

		onRequest: function(val) {
			if (val == 0) {
				return 'On request';
			}
		},

		cityState: function(val) {
			var output = [];
			if (val.city) {
				output.push(val.city);
			}
			if (val.state) {
				output.push(val.state.slug);
			}
			return output.join(', ');
		},

		propertyType: function(val) {
			var output = [];

			// loop
			if (val) {
				val.forEach(function(v) {
					output.push(v.name);
				});
			}

			return output.join(', ');
		}
	},
	methods: {
		resetForm: function() {
			
		var selects = document.querySelectorAll("select");
		//var stateTags = document.querySelector(".state-tags");

		for(var i = 0; i < selects.length; i++) {
			selects[i].selectedIndex = 0
		}

		//stateTags.innerHTML = '<div></div>';
			
		this.search();
		
		this.showReset = false;

		},
		sortPropertiesByPrice: function(a, b) {
			const priceA = a.price.value;
			const priceB = b.price.value;
		
			let comparison = 0;
			if (priceA < priceB) {
				comparison = 1;
			} else if (priceA > priceB) {
				comparison = -1;
			}
			return comparison;
		},
		fetch: function() {
			var vm = this;

			jQuery
				.when(
					this.fetchProperties(),
					this.fetchTerms(),
					this.fetchAgents()
				)
				.then(() => {
					jQuery('.initial-loader').remove();
					vm.$el.removeAttribute('class');
					vm.loading = false;

					// follow router
					this.followRouter();
				});
		},

		fetchProperties: function() {
			var vm = this;

			return jQuery.getJSON(bootstrap.resource_path.properties, function(
				response
			) {
				// populate properties
				vm.properties = vm.filtered_properties = response;

				vm.loading = false;
			});
		},

		fetchTerms: function() {
			var vm = this;

			return jQuery.getJSON(bootstrap.resource_path.terms, function(
				response
			) {
				// populate terms
				vm.terms = response;
			});
		},

		fetchAgents: function() {
			var vm = this;

			return jQuery.getJSON(bootstrap.resource_path.agents, function(
				response
			) {
				// populate terms
				vm.agents = response;
			});
		},

		search: function() {
			this.showReset = true;
			var vm = this;

			// reset pagination
			this.page = 1;

			// get form params
			var params = jQuery('form', this.$el).serializeArray();
			var active_params = [];

			// update prices filter dropdown
			var offering_type = jQuery(
				'select[name="offering-type"] option:selected'
			)
				.text()
				.toLowerCase();

			// filter out empty params
			for (var i = 0; i < params.length; i++) {
				if (params[i].value != '') {
					// get obj value
					var val = params[i];

					// group multiple options in array
					if (val.name.indexOf('[]') !== -1) {
						// check if already exists
						var key = null;
						val.name = val.name.replace('[]', '');

						//
						if (active_params.length) {
							for (var n = 0; n < active_params.length; n++) {
								if (active_params[n].name == val.name) {
									key = n;
									break;
								}
							}
						}

						if (key !== null) {
							// create empty array
							if (!active_params[key]) {
								active_params[key] = {
									name: val.name,
									value: []
								};
							}

							active_params[key].value.push(val.value);
						} else {
							active_params.push({
								name: val.name,
								value: [val.value]
							});
						}
					} else {
						active_params.push(val);
					}
				}
			}


			var vm = this;
			// setTimeout(function(){
			// 	vm.propertiesCount = document.querySelectorAll('.property-card').length;
			// 	console.log('count ran', vm.propertiesCount)
			// }, 100);
			
			// search within map bounds
			var include_map_bounds =
				vm.layout == 'map' && typeof vm.map_bounds != 'undefined'
					? true
					: false;
			eventHub.$emit('search');

			// update active search params
			this.active_search_params = active_params;

			// if no parameters were passed, show all fetched properties
			if (!active_params.length && include_map_bounds === false) {
				this.filtered_properties = this.properties;
				return;
			} else {
				// clear filtered properties
				this.filtered_properties = [];
			}

			// loop properties and
			for (var i = 0; i < this.properties.length; i++) {
				var property = this.properties[i];

				// pass search conditions
				var pass = true;

				for (var n = 0; n < active_params.length; n++) {
					var term = active_params[n];

					switch (term.name) {
						// check on keywords
						case 'keywords':
							pass =
								property.name
									.toLowerCase()
									.indexOf(term.value.toLowerCase()) !== -1 ||
								(property.location.address &&
									property.location.address
										.toLowerCase()
										.indexOf(term.value.toLowerCase()) !==
										-1) ||
								(property.location.city &&
									property.location.city
										.toLowerCase()
										.indexOf(term.value.toLowerCase()) !==
										-1) ||
								(property.location.neighborhood &&
									property.location.neighborhood
										.toLowerCase()
										.indexOf(term.value.toLowerCase()) !==
										-1) ||
								(property.location.state_slug &&
									property.location.state_slug
										.toLowerCase()
										.indexOf(term.value.toLowerCase()) !==
										-1) ||
								(property.location.zip &&
									property.location.zip
										.toLowerCase()
										.indexOf(term.value.toLowerCase()) !==
										-1);
							break;

						case 'property-state':
							pass = vm.searchCheckTerm(
								term.value,
								property.terms
							);
							break;

						case 'property-type':
							pass = vm.searchCheckTerm(
								term.value,
								property.terms
							);
							break;

						case 'offering-type':
							pass = vm.searchCheckTerm(
								term.value,
								property.terms
							);
							break;

						case 'agents':
							pass = property.brokers
								? property.brokers.indexOf(
										parseInt(term.value)
								  ) !== -1
								: false;
							break;

						case 'city':
							pass =
								term.value.indexOf(property.location.city) !=
								-1;
							break;

						case 'neighborhood':
							pass =
								term.value.indexOf(
									property.location.neighborhood
								) != -1;
							break;

						case 'surface':
							pass = vm.searchCheckTermRange(
								term.value,
								property,
								'surface'
							);
							break;

						case 'price':
							pass = vm.searchCheckTermRange(
								term.value,
								property,
								'price'
							);
							break;

						case 'units':
							pass = vm.searchCheckTermRange(
								term.value,
								property,
								'units'
							);
							break;

						case 'status':
							pass = term.value.indexOf(property.status) != -1;
							break;

						// if an unknown parameter is passed
						default:
							pass = false;
							break;
					}

					if (!pass) break;
				}

				// search map bounds if it passed all filters
				if (pass === true && include_map_bounds === true) {
					pass = false;
					if (property.location.lat && property.location.lng) {
						pass = vm.map_bounds.contains(
							new google.maps.LatLng(
								property.location.lat,
								property.location.lng
							)
						);
					}
				}

				// conditions passed, append property
				if (pass === true && this.properties[i].status !== 'closed' ) {
					this.filtered_properties.push(this.properties[i]);
				}
			}
			
		},
		// function to check if property has term
		searchCheckTerm: function(needle, haystack) {
			// assume it's not passing
			var pass = false;

			if (needle.constructor === Array) {
				var vals = [];
				needle.forEach(val => vals.push(parseInt(val)));

				vals = vals.filter(val => {
					return haystack.indexOf(val) !== -1;
				});

				pass = vals.length > 0;
			} else {
				pass = haystack.indexOf(parseInt(needle)) !== -1;
			}

			return pass;
		},
		// function to check if property has term in range
		searchCheckTermRange: function(needle, haystack, key) {
			// assume it's not passing
			var pass = false;

			// init ranges
			var ranges = [];

			if (needle.constructor === Array) {
				needle.forEach(val => ranges.push(val.split('-')));
			} else {
				ranges.push(needle.split('-'));
			}

			// loop ranges
			ranges.forEach(range => {
				// extract min/max
				if (range.length > 1) {
					var min = range.shift();
					var max = range.shift();
				} else {
					var min = range.shift();
					var max = 999999999999;
				}

				// if property has availabilities check those
				if (haystack.availabilities && haystack.availabilities.length) {
					var obj_key = 'space_' + key + '_value';
					// loop availabilities to extract surface
					haystack.availabilities.forEach(availability => {
						var check =
							parseFloat(availability[obj_key]) > min &&
							parseFloat(availability[obj_key]) <= max;
						// set pass if passed :))
						if (check === true) pass = true;
					});
				} else {
					if (typeof haystack[key] === 'object') {
						var check =
							haystack[key].value > min &&
							haystack[key].value <= max;
					} else {
						var check = haystack[key] > min && haystack[key] <= max;
					}
					// set pass if passed :))
					if (check === true) pass = true;
				}
			});

			return pass;
		},
		paginate: function() {
			var vm = this;
			// reset
			this.paginated_properties = [];

			Object.keys(this.filtered_properties).forEach(key => {
				vm.paginated_properties.push(vm.filtered_properties[key]);
			});

			this.paginated_properties.splice(
				this.per_page * this.page,
				this.filtered_properties.length - this.per_page * this.page
			);
		},
		isActiveParam: function(param, value) {
			// assume not
			var result = false;

			//
			if (this.active_search_params.length) {
				for (var i = 0; i < this.active_search_params.length; i++) {
					if (this.active_search_params[i].name == param) {
						// check if a value is passed to compare
						if (typeof value != 'undefined') {
							if (this.active_search_params[i].value == value) {
								return true;
							}
						} else {
							result = true;
						}
						break;
					}
				}
			}

			return result;
		},
		highlight: function(idx) {
			eventHub.$emit('highlightMarker', idx);
		},
		dehighlight: function(idx) {
			eventHub.$emit('dehighlightMarker', idx);
		},
		followRouter: function() {
			// stop if router is undefined
			if (typeof VueRouter == 'undefined' || typeof router == 'undefined')
				return;

			var vm = this;

			vm.$nextTick(function() {
				var route_name = router.currentRoute.name;

				// if no route name provider, stop here
				if (route_name == null) return;

				if (
					typeof vm.terms[route_name] != 'undefined' &&
					vm.terms[route_name].length
				) {
					// find term id
					var id = null;
					vm.terms[route_name].forEach(function(val) {
						if (val.slug == router.currentRoute.params.val) {
							id = val.id;
						}
					});

					// preselect filter
					if (id) {
						var el = document.querySelector(
							'[name="' + route_name + '"]'
						);
						if (typeof el != 'undefined' && el != null) {
							el.value = id;
						} else {
							// create a hidden input if filter doesn't exist
							var input = document.createElement('input');
							input.setAttribute('type', 'hidden');
							input.setAttribute('name', route_name);
							input.setAttribute('value', id);
							vm.$el
								.querySelector('#property-search')
								.appendChild(input);
						}

						// trigger search
						vm.search();
					}
				}
			});
		}
	}
});
