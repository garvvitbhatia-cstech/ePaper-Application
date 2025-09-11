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
@media (max-width: 767px){
.posts article {
     width:100%;
}	
}
</style>
<!-- Section -->
<section class="newspapers">
<div class="posts">
@if($records->count()>0)
@php
$siteUrl = env('APP_URL');
@endphp
@foreach($records as $key => $row)
<article>
<a href="{{url('/newspaper-details/')}}/{{$row->id}}" class="image"><img src="{{$siteUrl}}/public/admin/images/newspapers/{{$row->front_image}}" alt="" /></a>
<h5>{!! date('d-m-Y',strtotime($row->paper_date)) !!}</h5>
</article>
@endforeach
@endif
</div>
</section>
@endsection