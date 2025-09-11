@php
$siteUrl = env('APP_URL');
@endphp
<header id="header">
<a href="{{route('index')}}" class="logo"><img width="100%" src="{{$siteUrl}}/public/img/logo.jpg" /></a>
<!--<ul class="icons">
<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>
<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>
<li><a href="#" class="icon brands fa-instagram"><span class="label">Instagram</span></a></li>
</ul>-->
</header>