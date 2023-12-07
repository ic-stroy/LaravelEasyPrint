@extends('layout.layout')

@section('title')
    {{-- Your page title --}}
@endsection
@section('content')
    @php
        if(isset($banner->image) && !is_array($banner->image)){
            $banner_images = json_decode($banner->image);
        }else{
            $banner_images = [];
        }
    @endphp
    <style>
        .delete_carusel_func{
            height: 20px;
            background-color: transparent;
            border: 0px;
            color: silver;
        }
        .carousel_image img{

        }
        .carousel_image{
            margin-right: 4px;
            transition: 0.4s;
        }
        .carousel_image:hover{
            border: lightgrey;
            transform: scale(1.02);
            background-color: rgba(0, 0, 0, 0.2);
        }
    </style>
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="text-muted font-14">
                {{translate('Banner list edit')}}
            </p>
            <form action="{{route('banner.update', $banner->id)}}" class="parsley-examples" method="POST" enctype="multipart/form-data">
                @csrf
                @method("PUT")
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Title')}}</label>
                        <input type="text" name="title" class="form-control" required value="{{$banner->title}}"/>
                    </div>
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Is active')}}</label>
                        <select id="is_active" class="form-select" name="is_active">
                            <option value="1" {{$banner->is_active == 1?'selected':''}}>{{translate('Active')}}</option>
                            <option value="0" {{$banner->is_active == 0?'selected':''}}>{{translate('No active')}}</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">{{translate('Text')}}</label>
                    <textarea class="form-control" name="text" id="text" required cols="20" rows="2">{{$banner->text }}</textarea>
                </div>
                <div class="mb-3">
                    <div class="row">
                        @php
                            if(!isset($banner_images->banner) || $banner_images->banner == ''){
                                 $banner_image = 'no';
                            }else{
                                $banner_image = $banner_images->banner;
                            }
                            $avatar_main = storage_path('app/public/banner/'.$banner_image);
                        @endphp
                        @if(file_exists($avatar_main))
                            <label class="form-label">{{translate('Banner image')}}</label>
                            <div class="">
                                <img src="{{asset('storage/banner/'.$banner_image)}}" alt="" height="200px">
                            </div>
                        @endif
                    </div>
                    <div class="mb-3 col-6">
                        <input type="file" name="image" class="form-control" value="{{old('image')}}"/>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="row">
                        @if(!is_array($banner_images))
                            @php
                                if(!isset($banner_images->carousel) || count($banner_images->carousel)<0){
                                     $carousel_images[] = 'no';
                                }else{
                                    $carousel_images = $banner_images->carousel;
                                }
                            @endphp
                            @foreach($carousel_images as $key => $carousel_image)
                                @if(file_exists(storage_path('app/public/banner/carousel/'.$carousel_image)))
                                    <div class="col-2 mb-3 carousel_image">
                                        <div class="d-flex justify-content-between">
                                            <img src="{{asset('storage/banner/carousel/'.$carousel_image)}}" alt="" height="200px">
                                            <button class="delete_carusel_func">X</button>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-6">
                        <label class="form-label">{{translate('Carousel image')}}</label>
                        <input type="file" name="carusel_images[]" class="form-control" multiple  value="{{old('image')}}"/>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">{{translate('Update')}}</button>
                    <button type="reset" class="btn btn-secondary waves-effect">{{translate('Cancel')}}</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let carousel_image = document.getElementsByClassName('carousel_image')
        let delete_carusel_func = document.getElementsByClassName('delete_carusel_func')
        let deleted_text = "{{translate('Carousel image was deleted')}}"
        let carousel_images = []
        @if(!is_array($banner_images))
            @php
                if(!isset($banner_images->carousel) || count($banner_images->carousel)<0){
                     $carousel_images = [];
                }else{
                    $carousel_images = $banner_images->carousel;
                }
            @endphp
            @foreach($carousel_images as $carousel_image)
                carousel_images.push("{{$carousel_image}}")
            @endforeach
        @endif
        function deleteCarouselFunc(item, val) {
            delete_carusel_func[item].addEventListener('click', function (e) {
                e.preventDefault()

                $.ajax({
                    url: '/api/delete-carousel',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id:"{{$banner->id}}",
                        carousel_name: carousel_images[item]
                    },
                    success: function(data){
                        console.log(data)
                    }
                });
                if(!carousel_image[item].classList.contains('display-none')){
                    carousel_image[item].classList.add('display-none')
                }
                toastr.success(deleted_text)
            })
        }
        Object.keys(delete_carusel_func).forEach(deleteCarouselFunc)
    </script>
@endsection
