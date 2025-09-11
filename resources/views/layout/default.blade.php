@php
$siteUrl = env('APP_URL');
@endphp
<!DOCTYPE HTML>
<html>
	<head>
		<title>Voice Of Jaipur | epaper</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="{{$siteUrl}}public/assets/css/main.css" />
        <link rel="stylesheet" href="{{$siteUrl}}public/admin/css/bootstrap.css" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!--<script src="{{$siteUrl}}public/assets/js/jquery.min.js"></script>-->
        <link href="{{ asset('public/admin/images/logo/cropped-voiclogo-3-192x192.jpg') }}" rel="apple-touch-icon" sizes="180x180">
    <link href="{{ asset('public/admin/images/logo/cropped-voiclogo-3-192x192.jpg') }}" rel="icon" sizes="32x32" type="image/png">
    <link href="{{ asset('public/admin/images/logo/cropped-voiclogo-3-192x192.jpg') }}" rel="icon" sizes="16x16" type="image/png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">

							<!-- Header -->
								@include('element.header')
                                @yield('content')
						</div>
					</div>

				<!-- Sidebar -->

			</div>

		<!-- Scripts -->
			
			<script src="{{$siteUrl}}public/assets/js/browser.min.js"></script>
			<script src="{{$siteUrl}}public/assets/js/breakpoints.min.js"></script>
			<script src="{{$siteUrl}}public/assets/js/util.js"></script>
			<script src="{{$siteUrl}}public/assets/js/main.js"></script>
            <script src="{{$siteUrl}}public/admin/js/bootstrap.bundle.min.js"></script>

	</body>
</html>