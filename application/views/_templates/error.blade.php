@include('_includes/head')
<body class="errorpage">
	<div class="page">
		<h1>@yield('pagetitle')</h1>
		<div class="main">
			<div class="primary">
				@yield('primary')
			</div>
			<div class="secondary"></div>
		</div>
	</div>
</body>
</html>