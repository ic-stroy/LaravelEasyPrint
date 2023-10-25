@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <h4 class="mt-0 header-title">{{__('Company lists')}}</h4>
            <div class="dropdown float-end">
                <a class="form_functions btn btn-success" onclick="createCompany()" href="{{route('company.create')}}">{{__('Create')}}</a>
            </div>
            <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Delivery price')}}</th>
                        <th>{{__('Address')}}</th>
                        <th>{{__('Updated_at')}}</th>
                        <th class="text-center">{{__('Functions')}}</th>
                    </tr>
                </thead>
                <tbody class="table_body">
                    @php
                        $i = 0
                    @endphp
                    @foreach($companies as $company)
                        @php
                            $i++
                        @endphp
                        <tr>
                            <th scope="row">
                                <a class="show_page" href="{{route('company.show', $company->id)}}">{{$i}}</a>
                            </th>
                            <td>
                                <a class="show_page" href="{{route('company.show', $company->id)}}">
                                    @if(isset($company->delivery_price)){{ $company->delivery_price }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company.show', $company->id)}}">
                                    @if(isset($company->name)){{ $company->name }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company.show', $company->id)}}">
                                    @if(isset($company->address->id)){{ $company->address->region??'' }} {{ $company->address->district??'' }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td>
                                <a class="show_page" href="{{route('company.show', $company->id)}}">
                                    @if(isset($company->updated_at)){{ $company->updated_at }}@else <div class="no_text"></div> @endif
                                </a>
                            </td>
                            <td class="function_column">
                                <div class="d-flex justify-content-center">
                                    <a class="form_functions btn btn-info" onclick="editCompany({{$company->id}})"><i class="fe-edit-2"></i></a>
                                    <form action="{{route('company.destroy', $company->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="form_functions btn btn-danger" type="submit"><i class="fe-trash-2"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        let edit_company = document.getElementById('edit_company')
        function createCompany(){
            refreshData('')
            window.location.href = "{{route('company.create')}}"
        }
        function editCompany(id){
            refreshData(id)
            window.location.href = `/company/${id}/edit`
        }
        function refreshData() {
            if(localStorage.getItem('district') != undefined && localStorage.getItem('district') != null){
                localStorage.removeItem('district')
            }
            if(localStorage.getItem('region') != undefined && localStorage.getItem('region') != null){
                localStorage.removeItem('region')
            }
            if(localStorage.getItem('address_lat') != undefined && localStorage.getItem('address_lat') != null){
                localStorage.removeItem('address_lat')
            }
            if(localStorage.getItem('address_long') != undefined && localStorage.getItem('address_long') != null){
                localStorage.removeItem('address_long')
            }
            if(localStorage.getItem('company_delivery_price') != undefined && localStorage.getItem('company_delivery_price') != null){
                localStorage.removeItem('company_delivery_price')
            }
            if(localStorage.getItem('company_name') != undefined && localStorage.getItem('company_name') != null){
                localStorage.removeItem('company_name')
            }
            if(localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null){
                localStorage.removeItem('region_id')
            }
            if(localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null) {
                localStorage.removeItem('district_id')
            }
        }

    </script>
@endsection
