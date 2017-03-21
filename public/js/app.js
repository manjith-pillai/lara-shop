var App = App || {};
var _placeSearch, _autocomplete,_lat,_lng,_geocoder;

App.controllers = {
	validation_data: {},

	init: function() {
		on($$('search'), 'oninput', App.controllers.search);
	},
    search:function(){

        var searchText=encodeURIComponent($$('search').value);
        var lat=$$('lat').value.toString().replace('.','@');
        var lng=$$('lng').value.toString().replace('.','@');

        //App.utils.show($$('small_loader'));
        if(searchText.length>=3){
            App.utils.ajax('/Mumbai/search/'+ searchText+'/'+lat+'/'+lng, {
                'method': 'GET',
                'callback': App.controllers.searchResults
            });
        }
    },
    searchResults:function(data){

    }

};
App.utils.onload(App.controllers.init);