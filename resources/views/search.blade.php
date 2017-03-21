@extends('layoutn.main')
@section('content')
@include('layoutn.header')
@include('sign-in-modal')
<div style="display:none" id="booking_id"></div>
<input id="lat" type="hidden"/>
<input id="lng" type="hidden"/>
<input id="city" type="hidden"/>


<div class="container search-container" style="margin-top:25px;position: relative;">
<div class="inner">
    <div class="row">
      <div class="col-md-3 col-sm-12" id="sortorder" style="max-height: 350px;z-index:100">
      <div id="sort-filter" class="hidden-lg hidden-md">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#s-b" aria-controls="s-b" role="tab" data-toggle="tab">Sort by <span style="float: right;"> <img src="{{URL::asset('img/assets/sort-white.png')}}" alt="" style="width: 16px"></span></a></li>
    <li role="presentation"><a href="#f" aria-controls="f" role="tab" data-toggle="tab">Filter
    <span style="float: right;"> <img src="{{URL::asset('img/assets/tool-white.png')}}" alt="" style="width: 16px"></span></a></li>
    
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="s-b">
        <div class="sort-collapse sorting">
            
               <ul class="sortingparameter">
               <input type="hidden" value="desc" class="sortorderval">
                <li id="rating" class="" style="cursor: pointer" onclick="sortBY('rating')"><span> <img src="{{URL::asset('img/assets/rating-s.png')}}" alt="" style="width: 14px;margin-bottom: 5px;margin-right: 10px;"></span>Restaurant Rating</li>
                <li id="deliverytime" class=" " style="cursor: pointer" onclick="sortBY('timing')"> <span> <img src="{{URL::asset('img/assets/clock.png')}}" alt="" style="width: 13px;margin-bottom: 5px;margin-right: 10px;"></span>Delivery Time</li>
                <li id="price" class="" style="cursor: pointer"  onclick="sortBY('cost_two_people')"><span> <img src="{{URL::asset('img/assets/money.png')}}" alt="" style="width: 14px;margin-bottom: 4px;margin-right: 9px;"></span>Cost for Two</li>
            </ul> 
            </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="f">
        <ul><form action="{{URl::current()}}">
                <li style="padding-top:10px;padding-bottom: 10px">
                   <div data-toggle="collapse" href="#min-max-resp" style="cursor: pointer;"><span> <img src="{{URL::asset('img/assets/money.png')}}" alt="" style="width: 14px;margin-bottom: 4px;margin-right: 11px;"></span>Cost for Two
                      <span class="arrow-down"></span>
                   </div>
                   <div class="collapse min-max" id="min-max-resp">
                      <div class="form-group">
                          <input type="text" class="form-control" value="{{Input::get('min_price')}}" placeholder="Min Cost" name="min_price"/></input>
                      </div>
                          <div class="form-group">
                              <input type="text" class="form-control" placeholder="Max Cost" value="{{Input::get('max_price')}}" name="max_price"/></input>
                          </div>
                   </div>
                </li>
                <li style="padding-top:10px;padding-bottom: 10px">
                    <div data-toggle="collapse" href="#cuisine-resp" style="cursor: pointer;"><span> <img src="{{URL::asset('img/assets/Cuisine.png')}}" alt="" style="margin-bottom: 4px; margin-left: -2px;margin-right: 10px;width: 18px;"></span>Cuisine
                        <span class="arrow-down"></span>
                    </div>
                    <div>
                        <ul class="collapse" id="cuisine-resp" style="margin-top: 5px">
                             <?php $restcusines[] = Input::has('restcusines') ? Input::get('restcusines') : [] ; ?>
                    @for($i=0,$flag=0;$i<sizeof($restaurantDetails['restcusines']);$i++)
                            <li>
                                <input type="checkbox" name="restcusines[]" value="{{$restaurantDetails['restcusines'][$i]->cuisine_name}}" {{in_array($restaurantDetails['restcusines'][$i]->cuisine_name, $restcusines ) ? 'checked' : ''  }}> <span> {{$restaurantDetails['restcusines'][$i]->cuisine_name}}</span>
                            </li>
                           @endfor
                          
                        </ul>
                    </div>
                     <input type="submit" value="Apply" style="background-color: #975ba5;border: #975ba5; width: 100%;margin-top:5px;color: #fff; text-align: center; line-height: 2.5; cursor: pointer;"></input>  
                </li>
            </form></ul>
        </div>
    
      </div>
        </div>
    </div>

        <div class="outer" style="position: absolute;width: 250px;margin-bottom: 15px">
        <div class="hidden-xs hidden-sm">
        
            <div class="sort-by s-f-head" onclick="toggleSortOrder()">
                Sort By
                <span style="float: right;"> <img src="{{URL::asset('img/assets/line.png')}}" alt="" style="width: 16px"></span>
            </div>
            <div class="sort-collapse" id="sorting">
            
               <ul class="sortingparameter">
               <input type="hidden" value="desc" id="sortorderval">
                <li id="rating" class="" style="cursor: pointer" onclick="sortBY('rating')"><span> <img src="{{URL::asset('img/assets/rating-s.png')}}" alt="" style="width: 14px;margin-bottom: 5px;margin-right: 10px;"></span>Restaurant Rating</li>
                <li id="deliverytime" class=" " style="cursor: pointer" onclick="sortBY('timing')"> <span> <img src="{{URL::asset('img/assets/clock.png')}}" alt="" style="width: 13px;margin-bottom: 5px;margin-right: 10px;"></span>Delivery Time</li>
                <li id="price" class="" style="cursor: pointer"  onclick="sortBY('cost_two_people')"><span> <img src="{{URL::asset('img/assets/money.png')}}" alt="" style="width: 14px;margin-bottom: 4px;margin-right: 9px;"></span>Cost for Two</li>
            </ul> 
            </div>

          </div> 
          
          <div class="hidden-xs hidden-sm">
          <form action="{{URl::current()}}">
            <div class="filters-tab s-f-head">
                Filter
                <span style="float: right;"> <img src="{{URL::asset('img/assets/tool.png')}}" alt="" style="width: 16px"></span>
            </div>
            <ul class="cuisine-outer" id="filters" style="padding-left: 5px;">
                <li>
                   <div data-toggle="collapse" href="#m-coll" style="cursor: pointer;margin-bottom: 5px"><span> <img src="{{URL::asset('img/assets/money.png')}}" alt="" style="width: 14px;margin-bottom: 4px;margin-right: 11px;"></span>Cost for Two
                  <span class="arrow-down"></span>
                   </div>
                   <div class="collapse in min-max" id="m-coll">
                     <div class="form-group">
                        <input type="text" class="form-control" value="{{Input::get('min_price')}}" placeholder="Min Cost" name="min_price"/></input>
                    </div>
                      <div class="form-group">
                        <input type="text" class="form-control" placeholder="Max Cost" value="{{Input::get('max_price')}}" name="max_price"/></input>
                      </div>
                   </div>
                </li>
                <li>
                    <div data-toggle="collapse" href="#cuisine" style="cursor: pointer;"><span> <img src="{{URL::asset('img/assets/Cuisine.png')}}" alt="" style="margin-bottom: 4px; margin-left: -2px;margin-right: 10px;width: 18px;"></span>Cuisine
                    <span class="arrow-down"></span>
                    </div>
                    <div id="cuisine" class=" collapse in cuisine-outer-collapse">
                    
                        <ul>
                        <?php $restcusines[] = Input::has('restcusines') ? Input::get('restcusines') : [] ; ?>
                    @for($i=0,$flag=0;$i<sizeof($restaurantDetails['restcusines']);$i++)
                            <li>
                                <input type="checkbox" name="restcusines[]" value="{{$restaurantDetails['restcusines'][$i]->cuisine_name}}" {{in_array($restaurantDetails['restcusines'][$i]->cuisine_name, $restcusines ) ? 'checked' : ''  }}><span> {{$restaurantDetails['restcusines'][$i]->cuisine_name}}</span>
                            </li>
                           @endfor
                          
                        </ul>
                    </div>

                </li>
           <input type="submit" value="Apply" style="background-color: #975ba5;border: #975ba5; width: 100%;margin-top:5px;color: #fff; text-align: center; line-height: 2.5; cursor: pointer;"></input>  
            </ul>
            </form>
           </div>


</div>



        <div class="col-md-9 pad-md">

            <div class="col-xs-12 form-search" style="margin-bottom: 25px;display: none;">
               
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Search for Food or Restaurant ">
      <span class="searchdiv">
        <button class="btn btn-default searchbtn" type="button">Find</button>
      </span>
    </div>
  
            </div>
            
            
            <div class="row" style="margin-bottom: 35px;min-height:500px">
            
            @for($i=0; $i<sizeof($restaurantInfo); $i)
            @for($j=0;$j<3 && $i<sizeof($restaurantInfo);$j++,$i++)
                
                <div class="col-sm-4 col-xs-12">
                    <!-- <div>
                        <img src="{{URL::asset('image/'.$restaurantInfo[$i]->img)}}" class="img-responsive" alt="..." style="width: 197px; height: 131px;">
                    </div> -->
                    <div class="" style="height: 250px;cursor: pointer;position:relative" onclick="location.assign('/{{$city}}/{{$restaurantInfo[$i]->url_name}}')">
                      <div class="card">
                        <div style="height: 130px">
                            <img src="{{URL::asset('image/'.$restaurantInfo[$i]->img)}}" class="img-responsive center-block" alt="..." style="width: 240px; height: 130px;">
                        </div>
                        <div style="">
                          <div class="" style="background:#fff;padding:5px"><div style="font-weight: bold;font-size: 14px">{{$restaurantInfo[$i]->name}}</div>
                          <div>
                          @if (!empty($restaurantInfo[$i]->area_name))    
                          <span>{{$restaurantInfo[$i]->area_name}}, </span>
                          @elseif (!empty($restaurantInfo[$i]->address))    
                          <div style="overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">{{$restaurantInfo[$i]->address}}, </div>
                          @endif
                          <span>{{$restaurantInfo[$i]->city_name}}</span>
                          </div>

                          <div style="height: 20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;">{{$restaurantInfo[$i]->cuisine_name}}</div>
                          @if($restaurantInfo[$i]->is_homely == '1')
                          <!--<div class="zapdel-homley" data-toggle="tooltip" data-placement="top" title="Click To Know More"><a href="{{url('zappmeal')}}"><img src="{{URL::asset('/img/assets/mo.jpg')}}" style="width:50px" alt=""></a></div>-->
                          @endif
                        </div>
                          
                        </div>
                      </div>

                    </div>
                  </div>  
                
                 @endfor
              @endfor
            </div>
            

        </div>

    </div>
    </div>
</div>
<script type="text/javascript" src="{{ URL::asset('js/utils.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('js/app.js')}}?<?=rand();?>"></script>



<!-- JS Custom -->
<script src="{{ URL::asset('js/custom.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('js/cart.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('js/search.js')}}"></script>
 <script>
      $(function(){ 
    var maxAbsoluteTop = $('.inner').outerHeight() - $('.outer').outerHeight();
    var minAbsoluteTop = 0;
    $(window).scroll(function(){
      var windowTop = $(window).scrollTop();
      var actualTop = windowTop - 60;
      if ( actualTop <= maxAbsoluteTop && actualTop >= minAbsoluteTop) {
          $('.outer').css({ top: windowTop - 60 });
      } else if (actualTop > maxAbsoluteTop){
          $('.outer').css({ top: maxAbsoluteTop });
      } else {
          $('.outer').css({ top: minAbsoluteTop });
      }
    }); 
});
 $(function(){
      $('.outer').stickit({screenMinWidth: 1280});
    
  $('[data-toggle="tooltip"]').tooltip();

     });
    </script>
    </script>

@stop