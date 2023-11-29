let subcategory_exists = document.getElementById('subcategory_exists')
let product_exists = document.getElementById('product_exists')
let warehouse_exists = document.getElementById('warehouse_exists')
let coupon_type = document.getElementById('coupon_type')
let coupon_percent = document.getElementById('coupon_percent')
let coupon_price = document.getElementById('coupon_price')
let coupon_price_input = document.getElementById('coupon_price_input')
let coupon_percent_input = document.getElementById('coupon_percent_input')

let sub_category = {}

let category_id = document.getElementById('category_id')
let subcategory_id = document.getElementById('subcategory_id')
let product_id = document.getElementById('product_id')
let warehouse_id = document.getElementById('warehouse_id')

function couponAddOption(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    if(item.id == coupon_subcategory_id){
        option.selected = true
    }
    subcategory_id.add(option)
}
if(coupon_subcategory_id != ''){
    subcategory_id.innerHTML = ""
    product_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/subcategory/${coupon_category_id}`,
            type:'GET',
            success: function (data) {
                if(subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.remove('display-none')
                }
                data.data.forEach(couponAddOption)
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_product
                disabled_option.disabled = true
                subcategory_id.add(disabled_option)
                let all_subcategories = document.createElement('option')
                all_subcategories.text = text_all_subcategory_products
                all_subcategories.value = "all"
                subcategory_id.add(all_subcategories)
            },
            error: function (e) {
                if(!subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.add('display-none')
                }
            }
        })
    })
}else{
    let all_subcategories = document.createElement('option')
    all_subcategories.text = text_all_subcategory_products
    all_subcategories.value = "all"
    all_subcategories.selected = true
    subcategory_id.add(all_subcategories)
}
function couponAddOptionToProduct(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    if(item.id == coupon_product_id){
        option.selected = true
    }
    product_id.add(option)
}
if(coupon_product_id != undefined && coupon_product_id != '' && coupon_product_id != null){
    product_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/get-products-by-category?category_id=${coupon_subcategory_id}`,
            type:'GET',
            success: function (data) {
                if(product_exists.classList.contains('display-none')){
                    product_exists.classList.remove('display-none')
                }
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_product
                disabled_option.disabled = true
                product_id.add(disabled_option)
                let all_products = document.createElement('option')
                all_products.text = text_all_products
                all_products.value = "all"
                product_id.add(all_products)
                data.data[0].products.forEach(couponAddOptionToProduct)
            },
            error: function (e) {
                if(!product_exists.classList.contains('display-none')){
                    product_exists.classList.add('display-none')
                }
                if(!warehouse_exists.classList.contains('display-none')){
                    warehouse_exists.classList.add('display-none')
                }
            }
        })
    })
}else{
    let all_products = document.createElement('option')
    all_products.text = text_all_products
    all_products.value = "all"
    all_products.selected = true
    product_id.add(all_products)
}
if(coupon_percent_value != ''){
    if(coupon_percent.classList.contains('display-none')){
        coupon_percent.classList.remove('display-none')
    }
    if(!coupon_price.classList.contains('display-none')){
        coupon_price.classList.add('display-none')
    }
}else if(coupon_price_value != ''){
    if(coupon_price.classList.contains('display-none')){
        coupon_price.classList.remove('display-none')
    }
    if(!coupon_percent.classList.contains('display-none')){
        coupon_percent.classList.add('display-none')
    }
}
function couponAddOptionToWarehouse(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name +' color '+ item.color +' size '+item.size
    if(item.id == coupon_warehouse_id){
        option.selected = true
    }
    warehouse_id.add(option)
}
if(coupon_warehouse_id != undefined && coupon_warehouse_id != '' && coupon_warehouse_id != null) {
    warehouse_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../companies/product/get-warehouses-by-product?product_id=${coupon_product_id}`,
            type:'GET',
            success: function (data) {
                if(warehouse_exists.classList.contains('display-none')){
                    warehouse_exists.classList.remove('display-none')
                }
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_product
                disabled_option.disabled = true
                warehouse_id.add(disabled_option)
                let all_warehouses = document.createElement('option')
                all_warehouses.text = text_all_warehouses
                all_warehouses.value = "all"
                warehouse_id.add(all_warehouses)
                data.data.forEach(couponAddOptionToWarehouse)
            },
            error: function (e) {
                if(!warehouse_exists.classList.contains('display-none')){
                    warehouse_exists.classList.add('display-none')
                }
            }
        })
    })
}else{
    let all_warehouses = document.createElement('option')
    all_warehouses.text = text_all_products
    all_warehouses.value = "all"
    all_warehouses.selected = true
    warehouse_id.add(all_warehouses)
}


function addOption(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    subcategory_id.add(option)

}
category_id.addEventListener('change', function () {
    subcategory_id.innerHTML = ""
    product_id.innerHTML = ""
    warehouse_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/subcategory/${category_id.value}`,
            type:'GET',
            success: function (data) {
                if(subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.remove('display-none')
                }
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_sub_category
                disabled_option.selected = true
                disabled_option.disabled = true
                subcategory_id.add(disabled_option)
                let all_products = document.createElement('option')
                all_products.text = text_all_products
                all_products.value = "all"
                subcategory_id.add(all_products)
                data.data.forEach(addOption)
            },
            error: function (e) {
                if(!subcategory_exists.classList.contains('display-none')){
                    subcategory_exists.classList.add('display-none')
                }
                if(!product_exists.classList.contains('display-none')){
                    product_exists.classList.add('display-none')
                }
                if(!warehouse_exists.classList.contains('display-none')){
                    warehouse_exists.classList.add('display-none')
                }
            }
        })
    })
})
function addOptionToProduct(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    product_id.add(option)
}
subcategory_id.addEventListener('change', function () {
    product_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../api/get-products-by-category?category_id=${subcategory_id.value}`,
            type:'GET',
            success: function (data) {
                if(product_exists.classList.contains('display-none')){
                    product_exists.classList.remove('display-none')
                }
                let disabled_option = document.createElement('option')
                disabled_option.text = text_select_product
                disabled_option.selected = true
                disabled_option.disabled = true
                product_id.add(disabled_option)
                let all_products = document.createElement('option')
                all_products.text = text_all_products
                all_products.value = "all"
                product_id.add(all_products)
                data.data[0].products.forEach(addOptionToProduct)
            },
            error: function (e) {
                if(!product_exists.classList.contains('display-none')){
                    product_exists.classList.add('display-none')
                }
                if(!warehouse_exists.classList.contains('display-none')){
                    warehouse_exists.classList.add('display-none')
                }
            }
        })
    })
})
coupon_type.addEventListener('change', function () {
    if(coupon_type.value == 'percent'){
        if(coupon_percent.classList.contains('display-none')){
            coupon_percent.classList.remove('display-none')
        }
        if(!coupon_price.classList.contains('display-none')){
            coupon_price.classList.add('display-none')
        }
        coupon_percent_input.value = ''
    }else if(coupon_type.value == 'price'){
        if(coupon_price.classList.contains('display-none')){
            coupon_price.classList.remove('display-none')
        }
        if(!coupon_percent.classList.contains('display-none')){
            coupon_percent.classList.add('display-none')
        }
        coupon_price_input.value = ''
    }
})
function addOptionToWarehouse(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name +' color '+ item.color +' size '+item.size
    warehouse_id.add(option)
}
product_id.addEventListener('change', function () {
    warehouse_id.innerHTML = ""
    $(document).ready(function () {
        $.ajax({
            url:`/../companies/product/get-warehouses-by-product?product_id=${product_id.value}`,
            type:'GET',
            success: function (data) {
                if(warehouse_exists.classList.contains('display-none')){
                    warehouse_exists.classList.remove('display-none')
                }
                let all_warehouses = document.createElement('option')
                all_warehouses.text = text_all_warehouses
                all_warehouses.value = "all"
                warehouse_id.add(all_warehouses)
                data.data.forEach(addOptionToWarehouse)
            },
            error: function (e) {
                if(!warehouse_exists.classList.contains('display-none')){
                    warehouse_exists.classList.add('display-none')
                }
            }
        })
    })
})
