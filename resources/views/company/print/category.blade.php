@extends('company.layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{translate('Category lists')}}</h4>
            <table class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                <tr>
                    <th></th>
                </tr>
                </thead>
                <tbody class="table_body">
                @php
                    $i = 0
                @endphp
                @foreach($categories as $category)
                    @php
                        $i++
                    @endphp
                    <tr>
                        <td>
                            <a class="show_page" href="{{route('print.category.product', $category->id)}}">
                                @if(isset($category->name)){{ $category->name }}@else <div class="no_text"></div> @endif
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
