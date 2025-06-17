@extends('front_layouts.app')

@section('page_title', 'قائمة الكتب')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h4 text-light">قائمة الكتب</h1>
            @if (Auth::user()->hasRole('admin'))
                <a href="{{ route('books.create') }}" class="btn btn-primary">إضافة كتاب جديد</a>
            @endif
        </div>

        {{-- @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif --}}


        <div class="row g-4">
            @forelse($books as $book)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-lg rounded-4 overflow-hidden  book-card">
                        <div class="position-relative">
                            <img src="{{ $book->image ? asset('image/books/' . $book->image) : asset('default-book.png') }}"
                                class="card-img-top img-fluid book-img" alt="صورة الكتاب"
                                style="height:240px;object-fit:cover;transition:transform .3s;">
                            <span
                                class="position-absolute top-0 end-0 m-2 badge bg-{{ $book->status === 'available' ? 'success' : 'danger' }}">
                                {{ $book->status === 'available' ? 'متاح' : 'معار' }}
                            </span>
                        </div>
                        <div class="card-body  d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1">{{ $book->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $book->author }}</h6>
                            <p class="mb-1 small"><strong>التصنيف:</strong> {{ $book->category }}</p>
                            <p class="text-truncate small mb-2" title="{{ $book->description }}">{{ $book->description }}
                            </p>
                            <div class="mt-auto d-flex flex-wrap gap-2">
                                @if (Auth::user()->hasRole('admin'))
                                    <a href="{{ route('books.edit', $book) }}"
                                        class="btn btn-sm btn-outline-primary px-3">تعديل</a>
                                    <form action="{{ route('books.destroy', $book) }}" method="POST"
                                        onsubmit="return confirm('هل أنت متأكد من حذف هذا الكتاب؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3">حذف</button>
                                    </form>
                                @endif
                                @if ($book->status === 'available')
                                    <form action="{{ route('borrow.store', $book) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm btn-outline-secondary px-3">استعارة</button>
                                    </form>
                                @else
                                    @if ($book->borrowings->where('user_id', auth()->id())->whereNull('returned_at')->count())
                                        <form action="{{ route('borrow.return', $book) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-sm btn-outline-success px-3">إرجاع</button>
                                        </form>
                                    @endif
                                @endif
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

<style>
    .book-card:hover .book-img {
        transform: scale(1.05);
        filter: brightness(0.95);
    }

    .book-card {
        transition: box-shadow .3s;
    }

    .book-card:hover {
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
    }
</style>
