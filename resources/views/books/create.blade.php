@extends('front_layouts.app')

@section('page_title', 'قائمة الكتب')

@section('content')
 <div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">إضافة كتاب</div>

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

                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <div class="mb-3">
                            <label for="title" class="form-label">عنوان الكتاب</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">المؤلف</label>
                            <input type="text" name="author" id="author" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">التصنيف</label>
                            <input type="text" name="category" id="category" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">حالة الكتاب</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="available">متاح</option>
                                <option value="borrowed">معار</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">وصف الكتاب</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                         <div class="mb-3">
                            <label for="image" class="form-label">الصورة</label>
                            <input  type="file" name="image" id="image" class="form-control" accept="image/*">
                        </div>


                        <button type="submit" class="btn btn-primary">حفظ الكتاب</button>
                        <a href="{{ route('books.index') }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
