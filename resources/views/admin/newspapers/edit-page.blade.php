@extends('layout.admin.dashboard')
@php
$siteUrl = env('APP_URL');
@endphp
@section('content')


<div class="page-heading">

   <div class="page-title">

      <div class="row">

         <div class="col-12 col-md-6 order-md-1 order-last">

            <h3>Edit Newspaper</h3>

         </div>

         <div class="col-12 col-md-6 order-md-2 order-first">

            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">

               <ol class="breadcrumb">

                  <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>

                  <li class="breadcrumb-item"><a href="{{url('/admin/newspapers')}}">Newspapers</a></li>

                  <li class="breadcrumb-item active" aria-current="page">Edit Newspaper</li>

               </ol>

            </nav>

         </div>

      </div>

   </div>

   <section class="section">

<form class="form w-100" id="pageForm" action="#">

      <div class="row">
      
      

         <div class="col-12 col-md-9">

            <div class="card">

               <div class="card-header">

                  <h4 class="card-title">Edit Newspaper</h4>

               </div>

               <div class="card-body">

                  

                     <div class="row">
                     	
                     	 

                        <div class="col-md-4">

                           <div class="form-group">

                              <label for="basicInput">Start Date</label>

                              <input type="date" class="form-control" name="paper_date" value="{{$rowData->paper_date}}" id="paper_date">

                           </div>

                        </div>
                        
                        <div class="col-md-4">

                           <div class="form-group">

                              <label for="basicInput">End Date</label>

                              <input type="date" class="form-control" name="end_date" value="{{$rowData->end_date}}" id="end_date">

                           </div>

                        </div>

                        

                        <div class="col-md-3">

                           <div class="form-group">

                                 <label for="basicInput">PDF File</label>

                                <input type="file" name="image" id="image" value="" class="form-control">

                           </div>

                        </div>

                        @if($rowData->pdf_file != '')

                        <div class="col-md-3">

                           	<div class="form-group">

								<a href="{{env('APP_URL')}}public/img/newspapers/{{$rowData->pdf_file}}" target="_blank">Download PDF</a>

                           	</div>

                        </div>

                        @endif
                        
                        <div class="col-md-12">

                            <div class="form-group form-float">
                            <label class="form-label">Newspaper Images</label>
                            <div id="my-awesome-dropzone" class="dropzone"></div>
                            </div>

                        </div>
                        
                        <div class="col-md-12">
                    <div class="table-responsive text-nowrap">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#ID</th>
                        <th>Image</th>
                        
                        <th>Ordering</th>
                        <th>Date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0" id="replaceImagesHtml">
                      <tr>
                    <td colspan="10" class="text-center"><img src="{{ asset('public/admin/images/svg/oval.svg') }}" class="me-4" style="width: 3rem" alt="audio"></td>
                  </tr>
                      
                    </tbody>
                  </table>
                </div>
                  </div>
   

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

                           <!--end::Submit button-->

                        </div>
               </div>
         </div>

      </div>
      
       

 

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
$('#my-awesome-dropzone').attr('class', 'dropzone');
var myDropzone = new Dropzone('#my-awesome-dropzone', {
	url: "{{route('admin.newspaperUpload')}}",
	clickable: true,
	method: 'POST',
	maxFiles: 50,
	parallelUploads: 50,
	maxFilesize: 20,
	addRemoveLinks: false,
	dictRemoveFile: 'Remove',
	dictCancelUpload: 'Cancel',
	dictCancelUploadConfirmation: 'Confirm cancel?',
	dictDefaultMessage: 'Drop files here to upload',
	dictFallbackMessage: 'Your browser does not support drag n drop file uploads',
	dictFallbackText: 'Please use the fallback form below to upload your files like in the olden days',
	paramName: 'file',
	params: {'pid':'{{ $rowData->id }}'},
	forceFallback: false,
	createImageThumbnails: true,
	maxThumbnailFilesize: 5,
	//acceptedFiles: ".jpeg,.jpg,.webp,.png,.svg",
	acceptedFiles: "image/*",
	autoProcessQueue: true,
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	},
	init: function() {
		this.on('thumbnail', function(file) {
			if (file.width < 100 || file.height < 100) {
				file.rejectDimensions();
			} else {
				file.acceptDimensions();
			}
		});
	},
	accept: function(file, done) {
		file.acceptDimensions = done;
		file.rejectDimensions = function() {
			done('The image must be at least 100 x 100px')
		};
	}
});

myDropzone.on("complete", function(file) {
	var status = file.status;
	if (status == 'success') {

	}
	console.log(file);
});

var count = 1;
myDropzone.on("success", function(file, responseText) {
	var fnamenew = file.name;
	count++;
	filterData();
});

myDropzone.on("removedfile", function(file) {
	var fname = file.name;
	fname2 = fname.trim().replace(/["~!@#$%^&*\(\)_+=`{}\[\]\|\\:;'<>,.\/?"\- \t\r\n]+/g, '_');    
});

myDropzone.on("addedfile", function(file) {

}); 
$(document).ready(function(){
filterData();
});

function filterData(){
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'POST',
		data: {pid:'{{ $rowData->id }}'},
		url: "{{ url('/admin/get-newspaper-images') }}",
		success: function(response){
			$('#replaceImagesHtml').html(response);
		}
	});
}
function updateOrder(pid,ordering){
	$.ajax({
		headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
		type: 'POST',
		data: {pid:pid,ordering:ordering},
		url: "{{ url('/admin/update-newspaper-images-order') }}",
		success: function(response){
			filterData();
		}
	});
}
</script>
@endsection