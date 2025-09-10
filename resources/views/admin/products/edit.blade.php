@extends('admin.adminlayout')

@section('container')
<h2>Edit Product</h2>

@if ($errors->any())
  <div style="color:red">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div>
    <label>Name *</label><br>
    <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
  </div>

  <div>
    <label>Category</label><br>
    <select name="category_id">
      <option value="">-- None --</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" {{ (old('category_id', $product->category_id) == $c->id) ? 'selected' : '' }}>
          {{ $c->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label>Price *</label><br>
    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>
  </div>

  <div>
    <label>Stock</label><br>
    <input type="number" name="stock" value="{{ old('stock', $product->stock) }}">
  </div>

  <div>
    <label>Current Image</label><br>
    @if($product->image)
      <img src="{{ asset('storage/'.$product->image) }}" style="max-width:240px; max-height:160px; object-fit:cover;"><br>
      <label><input type="checkbox" name="remove_image" value="1"> Remove current image</label>
    @else
      <div>No image</div>
    @endif
  </div>

  <div>
    <label>Upload New Image</label><br>
    <input type="file" name="image" id="imageFileInput" accept="image/*">
  </div>

  <div>
    <label>Or Image URL</label><br>
    <input type="url" name="image_url" id="imageUrlInput" placeholder="https://..." value="{{ old('image_url') }}">
  </div>

  <div>
    <label>Description</label><br>
    <textarea name="description" rows="4">{{ old('description', $product->description) }}</textarea>
  </div>

  <div>
    <label>Preview:</label><br>
    <img id="previewImage" src="{{ old('image_url') ?: ($product->image ? asset('storage/'.$product->image) : '#') }}" style="{{ old('image_url') || $product->image ? '' : 'display:none;' }} max-width:240px; max-height:160px; object-fit:cover;">
  </div>

  <div style="margin-top:10px;">
    <button type="submit">Update Product</button>
    <a href="{{ route('admin.products.index') }}">Cancel</a>
  </div>
</form>

<script>
  // same preview JS as create
  document.getElementById('imageFileInput').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (ev) {
      const img = document.getElementById('previewImage');
      img.src = ev.target.result;
      img.style.display = 'block';
    };
    reader.readAsDataURL(file);
  });

  document.getElementById('imageUrlInput').addEventListener('input', function (e) {
    const url = e.target.value.trim();
    const img = document.getElementById('previewImage');
    if (!url && !document.getElementById('imageFileInput').files.length) {
      img.style.display = 'none';
      return;
    }
    img.src = url;
    img.style.display = 'block';
  });
</script>
@endsection
