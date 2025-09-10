@extends('admin.adminlayout')

@section('container')
<h1>Products</h1>
<a href="{{ route('admin.products.create') }}">Add Product</a> |
<a href="{{ route('admin.products.import.form') }}">Import CSV</a>

@if(session('success'))<div style="color:green">{{ session('success') }}</div>@endif

<table border="1" cellpadding="6">
<thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Price</th><th>Image</th><th>Actions</th></tr></thead>
<tbody>
@foreach($products as $p)
<tr>
<td>{{ $p->id }}</td>
<td>{{ $p->name }}</td>
<td>{{ $p->category?->name }}</td>
<td>{{ $p->price }}</td>
<td>@if($p->image)<img src="{{ asset('storage/'.$p->image) }}" width="80">@endif</td>
<td>
    <a href="{{ route('admin.products.edit',$p->id) }}">Edit</a>
    <form action="{{ route('admin.products.destroy',$p->id) }}" method="POST" style="display:inline">
        @csrf @method('DELETE')
        <button onclick="return confirm('Delete?')">Delete</button>
    </form>
</td>
</tr>
@endforeach
</tbody>
</table>

{{ $products->links() }}
@endsection
