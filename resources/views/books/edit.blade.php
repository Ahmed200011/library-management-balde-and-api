@extends('front_layouts.app')
@section('page_title', 'تعديل كتاب')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">تعديل كتاب</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('books.update', $book) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان الكتاب</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">المؤلف</label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">التصنيف</label>
                            <input type="text" name="category" id="category" value="{{ old('category', $book->category) }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="available" {{ old('status', $book->status) === 'available' ? 'selected' : '' }}>متاح</option>
                                <option value="borrowed" {{ old('status', $book->status) === 'borrowed' ? 'selected' : '' }}>معار</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea name="description" id="description" class="form-control">{{ old('description', $book->description) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">صورة الكتاب</label>
                            <input type="file" name="image" id="image" class="form-control">
                            @if ($book->image)
                                <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="img-thumbnail mt-2" style="max-width: 200px;">
                            @endif
                        </div>


                        <button type="submit" class="btn btn-primary">تحديث</button>
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
