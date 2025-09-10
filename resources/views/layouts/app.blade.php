<!doctype html>
<html>
<head><meta charset="utf-8"><title>Admin</title></head>
<body>
<nav>
  <a href="{{ route('admin.dashboard') }}">Dashboard</a> |
  <a href="{{ route('admin.products.index') }}">Products</a> |
  <a href="{{ route('logout') }}"
     onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
</nav>
<hr>
<div class="container">
  @if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif
  @yield('content')
</div>
</body>
</html>
