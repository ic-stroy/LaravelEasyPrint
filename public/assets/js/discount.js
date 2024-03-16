let subcategory_exists = document.getElementById('subcategory_exists')
let product_exists = document.getElementById('product_exists')

let category_id = document.getElementById('category_id')
let subcategory_id = document.getElementById('subcategory_id')
let product_id = document.getElementById('product_id')

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
                data.data[0].products_data.forEach(couponAddOptionToProduct)
            },
            error: function (e) {
                if(!product_exists.classList.contains('display-none')){
                    product_exists.classList.add('display-none')
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

function addOption(item, index){
    let option = document.createElement('option')
    option.value = item.id
    option.text = item.name
    subcategory_id.add(option)
}

category_id.addEventListener('change', function () {
    subcategory_id.innerHTML = ""
    product_id.innerHTML = ""
    if(!product_exists.classList.contains('display-none')){
        product_exists.classList.add('display-none')
    }
    $(document).ready(function () {
        $.ajax({
            url:`/../api/subcategory/${category_id.value}`,
            type:'GET',
            success: function (data) {
                console.log(data)
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
                console.log({'product':data})
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
                data.data[0].products_data.forEach(addOptionToProduct)
            },
            error: function (e) {
                if(!product_exists.classList.contains('display-none')){
                    product_exists.classList.add('display-none')
                }
            }
        })
    })
})
