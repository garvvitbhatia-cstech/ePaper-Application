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
.map{ width:100% !important}
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
.newsletter_input{width: 75% !important;float: left;}
.pdf_icon{position: absolute;
    right: 0px;
    top: -13px;}
.date_title{
	position: absolute;
    left: 0px;
    top: 10px;
    font-size: 18px;
    font-family: Tahoma, Geneva, sans-serif;}
.filter_date{position: absolute;
    top: 4px;
    right: 71px;
    width: 13% !important;}
.close_btn{position: absolute;
    right: 10px;
    top: 11px; cursor:pointer;}
.modal-footer{    text-align: center;
    display: block !important;
    font-size: 17px; font-family:Tahoma, Geneva, sans-serif}
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
	.newsletter_input{width: 60% !important;float: left;}
	.newsletter_div{border: 1px solid #ccc;
	width: 99%;
	padding: 19px;
	margin: 1% 1%;}
	
	.pdf_icon {
    position: absolute;
    right: 0px;
    top: -34px;
    z-index: 9;
}
.date_title {
        position: absolute;
        left: 0px;
        top: -9px;
        font-size: 13px;
        font-family: Tahoma, Geneva, sans-serif;
    }
.main-paper{ margin-top:28px}
.filter_date {
    position: absolute;
    top: -22px;
    right: 56px;
    width: 41% !important;
}
.modal-footer {
    font-size: 11px;
}
.close_btn {
    position: absolute;
    right: -13px;
    top: -15px;
    cursor: pointer;
}
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
      <div style="height:1000px; overflow-y: auto;"> @foreach($allPages as $key => $allPage)
        <div class="row">
          <div class="col-12" style="padding:15px"> <a href="{{url('/newspaper-details')}}/{{$id}}?page={{$key+1}}"><img src="{{$siteUrl}}/public/admin/images/newspapers/{{$allPage->title}}" style="border:1px solid #ccc" width="100%" /></a> Page {{$key+1}} </div>
        </div>
        @endforeach </div>
    </div>
    <div class="col-sm-12 col-md-10">
      <div style="width:100%; position:relative" class="dnon-mobile">
      
      <span class="date_title">{!! date('d/m/Y',strtotime($paperData->paper_date)) !!} - {!! date('d/m/Y',strtotime($paperData->end_date)) !!}</span>
      
      <div id="pagination">{!! $pages->links('pagination.front') !!}</div>
      
      <input type="text" value="{{$paperData->paper_date}}" id="dateInput" class="form-control filter_date" />
      
      @if($paperData->pdf_file != '')
      <a href="{{url('/download-pdf/')}}/{{$paperData->id}}" title="Download PDF"><img src="{{$siteUrl}}/public/img/511559_document_download_pdf_file_files_icon.png" class="pdf_icon" /></a>
      @endif
      </div>
      <div style="width:100%; position:relative" class="desktop-non">
      <span class="date_title">{!! date('d/m/Y',strtotime($paperData->paper_date)) !!} - {!! date('d/m/Y',strtotime($paperData->end_date)) !!}</span>
      <input type="text" value="{{$paperData->paper_date}}" id="dateInput2" class="form-control filter_date" />
      @if($paperData->pdf_file != '')
      <a href="{{url('/download-pdf/')}}/{{$paperData->id}}" title="Download PDF"><img src="{{$siteUrl}}/public/img/511559_document_download_pdf_file_files_icon.png" class="pdf_icon" /></a>
      @endif
      </div>
      <div class="main-paper">
      @foreach($pages as $key => $page) 
      <img src="{{$siteUrl}}/public/admin/images/newspapers/{{$page->title}}" alt="" id="target-image" usemap="#enewspaper" class="map" />
      <map name="enewspaper" id="current_page_image"></map>
      @endforeach 
      </div>
      <br />
      <div id="pagination" class="desktop-non">{!! $pages->links('pagination.front') !!}</div>
      <div class="newsletter_div"> <span style="font-family:Tahoma, Geneva, sans-serif; color:#000">Sign Up Newsletter</span><br />
        <span style="font-family:Tahoma, Geneva, sans-serif; font-size:13px;">Get updates by subscribe our Weekly newsletter</span><br />
        <br />
        <div>
          <input type="text" id="newsletetr_email" placeholder="Enter your email" class="form-control newsletter_input">
          <a style="float:right" id="submit_btn" onclick="saveNewsletter()" class="btn btn-primary">SUBSCRIBE</a>
          <div style="clear:both"></div>
        </div>
      </div>
    </div>

    <?php /*?><div class="col-sm-12 col-md-2 dnon-mobile" style="background-color:#fff; padding-top:20px">
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

</div><?php */?>
  </div>
</section>
<div class="modal fade" id="previewModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="position:relative"> <img width="100%" src="{{$siteUrl}}/public/img/logo.jpg" /> 
      <a data-bs-dismiss="modal"><img src="{{$siteUrl}}/public/img/1398917_circle_close_cross_incorrect_invalid_icon.png" class="close_btn" /></a>
      </div>
      <div class="modal-body text-center">
        <canvas id="previewCanvas" style="min-width: 20%;max-width: 100%;"></canvas>
      </div>
      <div class="modal-footer"> 
      Voice OF Jaipur<br />
Date: {!! date('d F Y',strtotime($paperData->paper_date)) !!} - {!! date('d F Y',strtotime($paperData->end_date)) !!} Page No: {{$pageNo}}<br />
Mail us your news on <a href="mailto:info@voiceofjaipur.com">info@voiceofjaipur.com</a>, <a href="mailto:somendra.harsh@gmail.com">somendra.harsh@gmail.com</a>
       </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>

<script>

const enabledDates = [<?php foreach($dates as $key => $date){?><?php if($key > 0){?>,<?php } ?>"<?php echo $date; ?>"<?php } ?>];

$( function() {
$("#dateInput").datepicker({
	beforeShowDay: function(date) {
		const formattedDate = $.datepicker.formatDate('mm-dd-yy', date);
		return [enabledDates.includes(formattedDate)];
	},
	onSelect: function(dateText, inst) {
		const myArray = dateText.split("/");
		window.location.href="{{url('/get-newspapaer')}}/"+myArray[2]+'-'+myArray[0]+'-'+myArray[1];
        console.log("Date selected: " + dateText);
    }
});
$("#dateInput2").datepicker({
	beforeShowDay: function(date) {
		const formattedDate = $.datepicker.formatDate('mm-dd-yy', date);
		return [enabledDates.includes(formattedDate)];
	},
	onSelect: function(dateText, inst) {
		const myArray = dateText.split("/");
		window.location.href="{{url('/get-newspapaer')}}/"+myArray[2]+'-'+myArray[0]+'-'+myArray[1];
        console.log("Date selected: " + dateText);
    }
});
});
  
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
				$('#submit_btn').html('SUBSCRIBE');
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