@extends('front_layouts.app')

@section('page_title', 'قائمة الكتب')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4">قائمة الكتب</h1>
            @if (Auth::user()->hasRole('admin'))
                <a href="{{ route('books.create') }}" class="btn btn-primary">إضافة كتاب جديد</a>
            @endif
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        <div class="row g-4">
            @forelse($books as $book)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">{{ $book->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $book->author }}</h6>
                            <p class="mb-1"><strong>التصنيف:</strong> {{ $book->category }}</p>
                            <p>
                                <span class="badge bg-{{ $book->status === 'available' ? 'success' : 'danger' }}">
                                    {{ $book->status === 'available' ? 'متاح' : 'معار' }}
                                </span>
                            </p>
                            <div class="mt-auto">
                                <div class="d-flex flex-wrap gap-2">
                                    @if (Auth::user()->hasRole('admin'))
                                        <a href="{{ route('books.edit', $book) }}"
                                            class="btn btn-sm btn-outline-primary">تعديل</a>

                                        <form action="{{ route('books.destroy', $book) }}" method="POST"
                                            onsubmit="return confirm('هل أنت متأكد من حذف هذا الكتاب؟');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">حذف</button>
                                        </form>
                                    @endif

                                    @if ($book->status === 'available')
                                        <form action="{{ route('borrow.store', $book) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-secondary">استعارة</button>
                                        </form>
                                    @else
                                        @if ($book->borrowings->where('user_id', auth()->id())->whereNull('returned_at')->count())
                                            <form action="{{ route('borrow.return', $book) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success">إرجاع</button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">لا توجد كتب مضافة حالياً.</div>
                </div>
            @endforelse
        </div>

    </div>
@endsection
