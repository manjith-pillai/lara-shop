/**
 * Created by Rishabh on 9/17/15.
 */
function sortBY(type)
{
    
    var path = window.location.pathname;

    var result1=path.split('?') ;
    
    var result=result1[0].split('/') ;
    var order = $$('sortorderval').value ;
    if(path.search("sort_by")!=-1)
    {
        var lat = result[3];;
        var lng = result[4];;
        var city = result[2];;
    }
    else
    {
        var lat = result[4];;
        var lng = result[5];;
        var city = result[1];;
    }

    lat=lat.replace('.','@');
    lng=lng.replace('.','@');
    if(lat=='' && lng=='')
    {
        alert("latitude and  longitude are not available ");
        var url = path ;
    }
    else
    {    
        var url='/sort_by/'+city+'/'+lat+'/'+lng + '/'+ type+ '/' + order;
    }

    
    
    url = url.replace(/\s/g, '');
    
    location.assign(url);
}

function toggleSortOrder()
{
    
    if($$('sortorderval').value == 'desc' )
    {
        $$('sortorderval').value  = 'asc' ;
    }
    else
    {
        $$('sortorderval').value = 'desc' ;
    }
}