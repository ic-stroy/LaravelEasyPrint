@extends('layout.layout')

@section('title')
    {{ translate("Language translate") }}
@endsection
@section('content')
<form class="form-horizontal" action="{{ route('translation.save') }}" method="POST">
    @csrf
    <input type="hidden" id="language_code" value="{{ $language->code??'' }}">
    <input type="hidden" name="id" value="{{ $language->id??'' }}">
    <h5 class="mb-md-0 h6">{{ $language->name??'' }}</h5>
    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
        <thead>
            <tr>
                <th>#</th>
                {{-- <th>{{ translate('status') }}</th> --}}
                <th>{{ translate('Key') }}</th>
                {{-- <th>{{ translate('company') }}</th> --}}
                <th> {{ translate('Translation') }}</th>
                {{-- <th>{{ translate('cars list') }}</th> --}}
                
            </tr>
        </thead>

        <tbody>
            @if (count($lang_keys) > 0)
                @php
                    $n = 1;
                @endphp
                @foreach ($lang_keys as $key => $translation)
                {{-- @dd($val->carList); --}}
                    <tr>
                        <td>{{ $n++ }}</td>
                        <td class="lang_key">{{ $translation->lang_key??'' }}</td>
                        <td class="lang_value">
                            <input type="text" class="checkboxDivPerewvod value" id="input"
                        style="width:100%" name="values[{{ $translation->lang_key??'' }}]"
                        @if (($traslate_lang = \App\Models\Translation::where('lang', $language->code??'')->where('lang_key', $translation->lang_key??'')->first()) != null) value="{{ $traslate_lang->lang_value??'' }}" @endif>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    <div class="row ">
        <div class="col-xl-6 col-md-6">
 
        </div>
        <div class="col-xl-6 col-md-6">
            <div class="form-group mt-2 text-right">
                <button type="button" class="btn btn-primary"
                    onclick="copyTranslation()">{{ translate('Copy Translations') }}</button>
                <button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
            </div>
        </div>
    </div>
</form>

<script src="{{ asset('assets/js/other/language.js') }}"></script>

@endsection
