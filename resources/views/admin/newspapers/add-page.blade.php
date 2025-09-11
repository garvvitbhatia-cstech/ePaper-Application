@extends('layout.admin.dashboard')

@section('content')


<div class="page-heading">

   <div class="page-title">

      <div class="row">

         <div class="col-12 col-md-6 order-md-1 order-last">

            <h3>Add New Newspaper</h3>

         </div>

         <div class="col-12 col-md-6 order-md-2 order-first">

            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">

               <ol class="breadcrumb">

                  <li class="breadcrumb-item"><a href="{{url('/admin')}}">Dashboard</a></li>

                  <li class="breadcrumb-item"><a href="{{url('/admin/newspapers')}}">Newspapers</a></li>

                  <li class="breadcrumb-item active" aria-current="page">Add Newspaper</li>

               </ol>

            </nav>

         </div>

      </div>

   </div>

   <section class="section">

      <div class="row">

         <div class="col-12 col-md-9">

            <div class="card">

               <div class="card-header">

                  <h4 class="card-title">Add Newspaper</h4>

               </div>

               <div class="card-body">

                  <form class="form w-100" id="pageForm" action="#">
                  
                  
                  <input type="hidden" name="type" id="type" value="Newspaper">

                     <div class="row">
                     	
                     	  

                        <div class="col-md-4">

                           <div class="form-group">

                              <label for="basicInput">State Date</label>

                              <input type="date" class="form-control" name="paper_date" id="paper_date">

                           </div>

                        </div>
                        
                        <div class="col-md-4">

                           <div class="form-group">

                              <label for="basicInput">End Date</label>

                              <input type="date" class="form-control" name="end_date" id="end_date">

                           </div>

                        </div>

                        

                        <div class="col-md-4">

                           <div class="form-group">

                                <label for="basicInput">PDF File</label>

                                <input type="file" name="image" id="image" value="" class="form-control">

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

                  </form>

               </div>

            </div>

         </div>

      </div>

   </section>

</div>

<script></script>

<!-- end plugin js -->



<script>

   let saveDataURL = "{{url('/admin/add-newspaper')}}";   

   let returnURL = "{{url('/admin/newspapers')}}";   

</script>

<script src="{{ asset('public/admin/js/pages/newspapers/add-page.js') }}"></script>

@endsection