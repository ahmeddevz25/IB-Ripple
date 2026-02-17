<!DOCTYPE html>
<html lang="en">
@include('index.layout.heads')
<body>
    @include('index.layout.header')
    @yield('content')
    @include('index.layout.footer')
    @include('index.layout.script')
</body>
</html>
