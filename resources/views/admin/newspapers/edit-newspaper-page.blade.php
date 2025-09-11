@extends('layout.admin.dashboard')
@php
$siteUrl = env('APP_URL');
@endphp
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.15/css/jquery.Jcrop.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-jcrop/0.9.15/js/jquery.Jcrop.js"></script>

<div class="page-heading">
   <div class="page-title">
      <div class="row">
         <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Edit Page</h3>
         </div>
         <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
               <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="{{$backUrl}}">Back to List</a></li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
   <section class="section">
<form class="form w-100" id="pageForm" action="#">
      <div class="row">
         <div class="col-12 col-md-7">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Edit Page</h4>
               </div>
               <div class="card-body">
                     <div class="row">
                        <div class="col-md-12">
                           <img src="{{$siteUrl}}/public/admin/images/newspapers/{{$rowData->title}}" id="image" width="100%"  class="map" />
                           
                        </div>
                     </div>
               </div>
            </div>
         </div>
         <div class="col-12 col-md-2">
         <div class="card">
        <div class="card-body">
        <div style="height:1000px; overflow-y:scroll">
            @foreach($allPages as $key => $allPage)
            <div class="row">
            <div class="col-12" style="padding:15px">
            <a href="{{ url('/admin/edit-newspaper-page',base64_encode($allPage->id)) }}"><img src="{{$siteUrl}}/public/admin/images/newspapers/{{$allPage->title}}" style="border:1px solid #ccc" width="100%" /></a>
            Page {{$key+1}}
            </div>
            </div>
            @endforeach
            </div>
        
        </div>
        </div>
         </div>
        <div class="col-12 col-md-3">
        <div class="card">
        <div class="card-body">
        <div class="text-left">
        <!--begin::Submit button-->
        <button type="button" id="form_submit" class="btn btn-sm btn-primary fw-bolder me-3 my-2">
        <span class="indicator-label" id="formSubmit">Submit</span>
        <span class="indicator-progress d-none">Please wait...
        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
        </button>
        <a href="{{$backUrl}}" class="btn btn-sm btn-danger fw-bolder me-3 my-2">Back</a>
        <!--end::Submit button-->
        </div>
        </div>
        </div>
      </div>
      <input type="hidden" id="height" />
      <input type="hidden" id="width" />
      <input type="hidden" id="x1" />
      <input type="hidden" id="y1" />
      <input type="hidden" id="x2" />
      <input type="hidden" id="y2" />
</div>
</form>
  </section>


<link href="{{$siteUrl}}/public/admin/css/dropzone.css" rel="stylesheet">
<script src="{{$siteUrl}}/public/admin/js/dropzone.js"></script>
<!-- end plugin js -->
<script>  
   let saveDataURL = "{{url('/admin/edit-newspaper/'.$row_id)}}";   
   let returnURL = "{{url('/admin/edit-newspaper/'.$row_id)}}";   
</script>
<script src="{{ asset('public/admin/js/pages/newspapers/add-page.js') }}"></script>
<script>
let img = document.getElementById('image');
let originalWidth = img.naturalWidth;
let displayWidth = img.width;

	$(document).ready(function(){
		$('#image').Jcrop({
			onSelect: function(c){
				$('#height').val(c.h);
				$('#width').val(c.w);
				$('#x1').val(c.x);
				$('#y1').val(c.y);
				$('#x2').val(c.x2);
				$('#y2').val(c.y2);
				savePartition();
			}
		})
	})
	function savePartition(){

        swal({
        title: "Are you sure want to save this area?",
        text: "We are saving selected area of this page",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((willDelete) => {
        if (willDelete) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'POST',
                url: "{{ url('/admin/save-newspaper-cordinates') }}",
                data: {height:$('#height').val(),width:$('#width').val(),x1:$('#x1').val(),y1:$('#y1').val(),x2:$('#x2').val(),y2:$('#y2').val(),page_id:'{{$rowData->id}}',original_width:originalWidth,display_width:displayWidth},
                success: function(msg){
					swal({
                            title: "Success!",
                            html: 'Partition saved.',
                            type: "success",
                            timer: 3000
                        });
				}
            });
        } else {
            swal("OK");
        }
        });
	
}
</script>


@endsection