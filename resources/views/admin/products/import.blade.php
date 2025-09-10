@extends('admin.adminlayout') {{-- Update this to match your actual layout --}}

@section('content')
    <div class="container">
        <h1>Import Products</h1>

        <form action="{{ route('admin.products.import.submit') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="import_file">Choose CSV File:</label>
                <input type="file" name="import_file" id="import_file" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Upload</button>
        </form>
    </div>
@endsection
