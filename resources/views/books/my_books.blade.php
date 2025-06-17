@extends('front_layouts.app')

@section('page_title', 'قائمة الكتب')

@section('content')
    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row g-4">
            {{-- @dd($borrowedBooks) --}}
            {{-- @dd($books) --}}
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col"> </th>
                        <th scope="col">اسم الكتاب</th>
                        <th scope="col">المؤلف</th>
                        <th scope="col"> </th>
                </thead>
            @forelse($books as $book)
                {{-- <div class="col-12 col-md-6 col-lg-4"> --}}
                        <tbody>
                            <tr>
                                <th scope="row">{{ $loop->iteration  }}</th>
                                <td>{{ $book->title }}</td>
                                <td>{{ $book->author }}</td>
                                <td>
                                    @if ($book->borrowings->where('user_id', auth()->id())->whereNull('returned_at')->count())
                                        <form action="{{ route('borrow.return', $book)}}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">إرجاع</button>
                                        </form>
                                    @endif
                                </td>

                            </tr>
                        </tbody>
                        {{-- </div> --}}
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">لا توجد كتب مضافة حالياً.</div>
                        </div>
                        @endforelse
                    </table>
        </div>

    </div>
@endsection
