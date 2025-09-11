@extends('layout.admin.dashboard')
@php
$siteUrl = env('APP_URL');
@endphp
@section('content')


<div class="page-heading">

   <div class="page-title">

      <div class="row">

         <div class="col-12 col-md-6 order-md-1 order-last">

            <h3>Edit Newsletter</h3>

         </div>

         <div class="col-12 col-md-6 order-md-2 order-first">

            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">

               <ol class="breadcrumb">

                  <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>

                  <li class="breadcrumb-item"><a href="{{url('/admin/newsletters')}}">Newsletters</a></li>

                  <li class="breadcrumb-item active" aria-current="page">Edit Newsletter</li>

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

                              <label for="basicInput">Email</label>

                              <input type="text" class="form-control" name="email" value="{{$rowData->email}}" id="email">

                           </div>

                        </div>
                        
 
                        
                        
                        
                        
   

                     </div>
                     
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
         <div class="col-12 col-md-3">
         
         <div class="card">

               <div class="card-body">
               
               
               </div>
         </div>

      </div>
      
       

 

</div>
</form>
  </section>

<!-- end plugin js -->

<script>  

   let saveDataURL = "{{url('/admin/edit-newsletter/'.$row_id)}}";   

   let returnURL = "{{url('/admin/edit-newsletter/'.$row_id)}}";   

</script>

<script src="{{ asset('public/admin/js/pages/newsletter/add-page.js') }}"></script>

@endsection