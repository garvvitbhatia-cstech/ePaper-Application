@php
$siteUrl = env('APP_URL');
@endphp
@extends('layout.default')
@section('content')
<style>
section.newspapers {
    padding-top: 30px !important;
}
.posts article {
     width: 230px;
}
.map{ width:600px !important}
.map{ margin:0 auto;}
.desktop-non{ display:none}
.modal-dialog {
        max-width: 90% !important;
        margin: 1.75rem auto;
    }
	.newsletter_div{border: 1px solid #ccc;
    width: 58%;
    padding: 19px;
    margin: 1% 21%;}
	.newsletter_input{width: 70% !important;float: left;}
@media (max-width: 767px){
	.posts article {
		 width:100%;
	}	
	.dnon-mobile{ display:none}
	.map{ width:100% !important}
	#header {
        padding-top:27px !important;
    }
	.desktop-non{ display:block}
	.newsletter_input{width: 68% !important;float: left;}
	.newsletter_div{border: 1px solid #ccc;
        width: 99%;
        padding: 19px;
        margin: 1% 1%;}
}
</style>
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/maphilight/1.4.2/jquery.maphilight.min.js"></script>
 
    <script type="text/javascript">
        $(function () {
           
		
		$('.map').maphilight({

  // fill the shape
  fill: true,

  // fill color
  fillColor: '000000',

  // fill opacity
  fillOpacity: 0.2,

  // outline the shape
  stroke: true,

  // stroke color
  strokeColor: 'ff0000',

  // stroke opacity
  strokeOpacity: 1,

  // stroke width
  strokeWidth: 1,

  // fade in the shapes on mouseover
  fade: true,

  // always show the hilighted areas
  alwaysOn: false,

  // never show the hilighted areas
  neverOn: false,

  // The name of an attribute to group areas by, or a selector for elements in the map to group. 
  // Or an array of the same
  // If this is present then all areas in the map which have the same attribute value as the hovered area will hilight as well
  groupBy: false,

  // If true, applies the class on the <img> to the wrapper div maphilight created.
  // If a string, that string is used as a class on the wrapper div.
  wrapClass: true,
  
  // apply a shadow to the shape
  shadow: false,
  shadowX: 0,
  shadowY: 0,
  shadowRadius: 6,
  shadowColor: '000000',
  shadowOpacity: 0.8,
  // Can be 'outside', 'inside', or 'both'.
  shadowPosition: 'outside',
  // Can be 'stroke' or 'fill'
  shadowFrom: false,
  
});

        });
    </script>
<!-- Section -->
<section class="newspapers" style="text-align:center">

<div class="row">
<div class="col-sm-12 col-md-2 dnon-mobile" style="background-color:#fff">
<div style="height:1000px; overflow-y: auto;">
@foreach($allPages as $key => $allPage)
<div class="row">
<div class="col-12" style="padding:15px">
<a href="{{url('/newspaper-details')}}/{{$id}}?page={{$key+1}}"><img src="{{$siteUrl}}/public/admin/images/newspapers/{{$allPage->title}}" style="border:1px solid #ccc" width="100%" /></a>
Page {{$key+1}}
</div>
</div>
@endforeach
</div>
</div>
<div class="col-sm-12 col-md-8">

<div id="pagination" class="dnon-mobile">{!! $pages->links('pagination.front') !!}</div>


@foreach($pages as $key => $page)

<img src="{{$siteUrl}}/public/admin/images/newspapers/{{$page->title}}" alt="" id="target-image" usemap="#enewspaper" class="map" />
<map name="enewspaper" id="current_page_image">
</map>

@endforeach


<br />
<div id="pagination" class="desktop-non">{!! $pages->links('pagination.front') !!}</div>

<div class="newsletter_div">
<div><input type="text" id="newsletetr_email" placeholder="Enter your email" class="form-control newsletter_input"> <a style="float:right" id="submit_btn" onclick="saveNewsletter()" class="btn btn-primary">Submit</a>
<div style="clear:both"></div>
</div>
</div>

</div>
<div class="col-sm-12 col-md-2 dnon-mobile" style="background-color:#fff; padding-top:20px">
	@if($paperData->pdf_file != '')
    <div style="margin-bottom:15px;">
	<a href="{{url('/download-pdf/')}}/9" style="font-family: Tahoma, Geneva, sans-serif;
    width: 100%;" target="_blank" class="btn btn-primary">Download PDF</a>
    </div>
    @endif
    <div><input type="date" class="form-control"></div>
    
    @foreach($records as $key => $row)
<div class="row">
<div class="col-12" style="padding:15px">
<a href="{{url('/newspaper-details/')}}/{{$row->id}}" class="image"><img src="{{$siteUrl}}/public/admin/images/newspapers/{{$row->front_image}}" style="border:1px solid #ccc" width="100%" alt="" /></a>
{!! date('d/m/Y',strtotime($row->paper_date)) !!} - {!! date('d/m/Y',strtotime($row->end_date)) !!}
</div>
</div>
@endforeach

</div>
</div>


</section>

<div class="modal fade" id="previewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <img width="100%" src="{{$siteUrl}}/public/img/logo.jpg" />
      </div>
      <div class="modal-body text-center">
        <canvas id="previewCanvas" style="min-width: 20%;max-width: 100%;"></canvas>
      </div>
      <div class="modal-footer">
        <a class="btn btn-danger" style="font-family:Tahoma, Geneva, sans-serif" data-bs-dismiss="modal">Close</a>
      </div>
    </div>
  </div>
</div>

<script>


let img = document.getElementById('target-image');
function loadModal(x,y,h,w) {
    let canvas = document.getElementById('previewCanvas');
    let ctx = canvas.getContext('2d');
    let image = new Image();
	image.src = img.src;
	image.width = 1000;

    image.onload = function () {
      canvas.width = w;
      canvas.height = h;
      ctx.drawImage(image, x, y, w, h, 0, 0, w, h);

      // Show Bootstrap Modal
      const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
      previewModal.show();
    };
  }
function getCordinates(){
	var width = img.width;
	$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			url: "{{ url('/get-cordinates') }}",
			data: {width:width,id:'{{$id}}',page_no:'{{$pageNo}}'},
			success: function(msg){
				$('#current_page_image').html(msg);
			}
		});
}
function saveNewsletter(){
	$('#submit_btn').html('...');
	$.ajax({
			headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			type: 'POST',
			url: "{{ url('/save-newsletter') }}",
			data: {newsletetr_email:$('#newsletetr_email').val()},
			success: function(msg){
				$('#submit_btn').html('Submit');
				$('#newsletetr_email').val('');
				 var obj = JSON.parse(msg);
                        if(obj['heading'] == "Success"){
                           alert(obj['msg']);
                        }else{
							alert(obj['msg']);
                        }
			}
		});
}
getCordinates();
</script>
@endsection