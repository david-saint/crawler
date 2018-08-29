<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{!! $page->title !!}</title>

	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<style>
		body,html {
			padding: 0;
			margin: 0;
		}
		body {
			height: 100vh;
			width: 100vw;
			overflow: hidden;
			position: relative;
		}
		iframe {
			width: 100%;
			height: 100%;
		}
		.ads {
			height: 100%;
			width: 100%;
			position: absolute;
		}
	</style>
</head>
<body>
	<iframe src="{{ $page->link }}" frameborder="0"></iframe>
	<div class="ads"></div>
</body>
</html>