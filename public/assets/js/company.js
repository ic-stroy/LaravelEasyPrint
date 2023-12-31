
    let region_id = document.getElementById('region_id')
    let map_id = document.getElementById('map')
    let district_id = document.getElementById('district_id')
    let region = document.getElementById('region')
    let district = document.getElementById('district')
    let address_lat = document.getElementById('address_lat')
    let address_long = document.getElementById('address_long')
    let company_name = document.getElementById('company_name')
    let company_delivery_price = document.getElementById('company_delivery_price')
    let first_name = document.getElementById('first_name')
    let last_name = document.getElementById('last_name')
    let edit_changed = false

    let text_select_sub_category = ""
    let text_all_subcategory_products = ""
    let text_all_products = ""
    let text_select_product = ""

    let current_region_index = -1

    let districts = {}
    let lat_lng = [41.32508826970849, 69.32866686970848];

    if(localStorage.getItem('district') != undefined && localStorage.getItem('district') != null){
        district.value = localStorage.getItem('district')
    }
    if(localStorage.getItem('region') != undefined && localStorage.getItem('region') != null){
        region.value = localStorage.getItem('region')
    }
    if(localStorage.getItem('address_lat') != undefined && localStorage.getItem('address_lat') != null){
        address_lat.value = localStorage.getItem('address_lat')
        document.getElementById('label_latitude').innerText = localStorage.getItem('address_lat')
    }
    if(localStorage.getItem('address_long') != undefined && localStorage.getItem('address_long') != null){
        address_long.value = localStorage.getItem('address_long')
        document.getElementById('label_longitude').innerText = localStorage.getItem('address_long')
    }
    if(company_delivery_price != undefined && company_delivery_price != null){
        if(localStorage.getItem('company_delivery_price') != undefined && localStorage.getItem('company_delivery_price') != null){
            company_delivery_price.value = localStorage.getItem('company_delivery_price')
        }
        company_delivery_price.addEventListener('change', function () {
            localStorage.setItem('company_delivery_price', company_delivery_price.value)
        })
    }
    if(company_name != undefined && company_name != null) {
        if (localStorage.getItem('company_name') != undefined && localStorage.getItem('company_name') != null) {
            company_name.value = localStorage.getItem('company_name')
        }
        company_name.addEventListener('change', function () {
            localStorage.setItem('company_name', company_name.value)
        })
    }
    if(first_name != undefined && first_name != null) {
        if (localStorage.getItem('first_name') != undefined && localStorage.getItem('first_name') != null) {
            first_name.value = localStorage.getItem('first_name')
        }
        first_name.addEventListener('change', function () {
            localStorage.setItem('first_name', first_name.value)
        })
    }
    if(last_name != undefined && last_name != null) {
        if (localStorage.getItem('last_name') != undefined && localStorage.getItem('last_name') != null) {
            last_name.value = localStorage.getItem('last_name')
        }
        last_name.addEventListener('change', function () {
            localStorage.setItem('last_name', last_name.value)
        })
    }
    function addOption(item, index){
        let region_option = document.createElement('option')
        region_option.value = index
        region_option.text = item.region
        if(localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null && localStorage.getItem('region_id') == index){
            region_option.selected = true
            edit_changed = true
        }else if(edit_changed == false){
            if(current_region == item.id){
                region_option.selected = true
                current_region_index = index
            }
        }
        region_id.add(region_option)
    }
    $(document).ready(function () {
    $.ajax({
        url:"/../api/get-districts",
        type:'GET',
        success: function (data) {
            data.data.forEach(addOption)
            if(localStorage.getItem('region_id') != undefined && localStorage.getItem('region_id') != null &&
                localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null) {
                districts_ = data.data[localStorage.getItem('region_id')].cities
                Object.keys(districts_).forEach(function (key) {
                    let district_selected_option = document.createElement('option')
                    district_selected_option.value = key
                    district_selected_option.text = districts_[key].name
                    if (localStorage.getItem('district_id') != undefined && localStorage.getItem('district_id') != null && localStorage.getItem('district_id') == key) {
                        district_selected_option.selected = true
                    }
                    district_id.add(district_selected_option)
                })
            }else if(page == true){
                if(current_region_index != -1){
                    districts_ = data.data[current_region_index].cities
                    Object.keys(districts_).forEach(function (key) {
                        let district_selected_option = document.createElement('option')
                        district_selected_option.value = key
                        district_selected_option.text = districts_[key].name
                        if (districts_[key].id == current_district) {
                            district_selected_option.selected = true
                        }
                        district_id.add(district_selected_option)
                    })
                }
            }
            region_id.addEventListener('change', function () {
                localStorage.setItem('region_id', region_id.value)
                district_id.innerHTML = ""
                let district_option_disabled = document.createElement('option')
                district_option_disabled.text = "Select district or city of "+ data.data[region_id.value].region
                district_option_disabled.disabled = true
                district_option_disabled.selected = true
                district_option_disabled.value = ''
                district_id.add(district_option_disabled)
                districts = data.data[region_id.value].cities
                Object.keys(districts).forEach(function (key) {
                    let district_option = document.createElement('option')
                    district_option.text = districts[key].name
                    district_option.value = key
                    district_id.add(district_option)
                })
            })
            district_id.addEventListener('change', function () {
                localStorage.setItem('region', data.data[region_id.value].id)
                localStorage.setItem('district', data.data[region_id.value].cities[district_id.value].id)
                localStorage.setItem('address_lat',data.data[region_id.value].cities[district_id.value].lat)
                localStorage.setItem('address_long', data.data[region_id.value].cities[district_id.value].long)
                localStorage.setItem('district_id', district_id.value)
                region.value = data.data[region_id.value].id
                district.value = data.data[region_id.value].cities[district_id.value].id
                address_lat.value = data.data[region_id.value].cities[district_id.value].lat
                address_long.value = data.data[region_id.value].cities[district_id.value].long
                location.reload()
            })
            if(page == true){
                lat_lng = [current_latitude != ''?current_latitude:41.32508826970849, current_longitude != ''?current_longitude:69.32866686970848];
            }
            if(localStorage.getItem('address_lat') != undefined && localStorage.getItem('address_lat') != null &&
                localStorage.getItem('address_long') != undefined && localStorage.getItem('address_long') != null){
                lat_lng = [localStorage.getItem('address_lat'), localStorage.getItem('address_long')];
            }
            mapOptions = {
                center:lat_lng,
                zoom:16
            };

            let marker = null;
            let map = new L.map('map' , mapOptions);
            let layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
            map.addLayer(layer);
            marker = new L.marker(lat_lng);
            marker.addTo(map);
            map.on('click', (event)=> {
                if(marker !== null){
                    map.removeLayer(marker);
                }
                marker = new L.marker([event.latlng.lat , event.latlng.lng]);
                marker.addTo(map);
                document.getElementById('label_latitude').innerText = event.latlng.lat;
                document.getElementById('label_longitude').innerText = event.latlng.lng;
                localStorage.setItem('address_lat', event.latlng.lat)
                localStorage.setItem('address_long', event.latlng.lng)
                address_lat.value = event.latlng.lat
                address_long.value = event.latlng.lng
                lat_lng = [event.latlng.lat, event.latlng.lng];
            })
        }
    })
})
    edit_changed = false
