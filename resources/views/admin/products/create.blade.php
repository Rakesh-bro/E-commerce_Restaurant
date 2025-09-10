@extends('admin.adminlayout')

@section('container')
<h2>Add Product</h2>

{{-- show validation errors --}}
@if ($errors->any())
  <div style="color:red">
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

{{-- success message --}}
@if(session('success'))
  <div style="color:green">{{ session('success') }}</div>
@endif

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div>
    <label>Name *</label><br>
    <input type="text" name="name" value="{{ old('name') }}" required>
  </div>

  <div>
    <label>Category</label><br>
    <select name="category_id">
      <option value="">-- None --</option>
      @foreach($categories as $c)
        <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
          {{ $c->name }}
        </option>
      @endforeach
    </select>
  </div>

  <div>
    <label>Price *</label><br>
    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required>
  </div>

  <div>
    <label>Stock</label><br>
    <input type="number" name="stock" value="{{ old('stock', 0) }}">
  </div>

  <div>
    <label>Upload Image (file)</label><br>
    <input type="file" name="image" id="imageFileInput" accept="image/*">
  </div>

  <div>
    <label>Or Image URL (paste an internet image link)</label><br>
    <input type="url" name="image_url" id="imageUrlInput" placeholder="https://example.com/img.jpg" value="{{ old('image_url') }}">
  </div>

  <div>
    <label>Description</label><br>
    <textarea name="description" rows="4">{{ old('description') }}</textarea>
  </div>

  <div>
    <label>Preview:</label><br>
    <img id="previewImage" src="#" alt="preview" style="display:none; max-width:240px; max-height:160px; object-fit:cover;">
  </div>

  <div style="margin-top:10px;">
    <button type="submit">Add Product</button>
    <a href="{{ route('admin.products.index') }}">Cancel</a>
  </div>
</form>

{{-- Image preview JS --}}
<script>
  // Preview when selecting a local file
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

  // If user types or pastes an image URL, show it in preview
  document.getElementById('imageUrlInput').addEventListener('input', function (e) {
    const url = e.target.value.trim();
    const img = document.getElementById('previewImage');
    if (!url) { 
      // if no url and no file selected, hide preview
      if (!document.getElementById('imageFileInput').files.length) img.style.display = 'none';
      return;
    }
    img.src = url;
    img.style.display = 'block';
  });
</script>
@endsection
