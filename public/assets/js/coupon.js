let coupon_type = document.getElementById('coupon_type')
let quantity_orders = document.getElementById('quantity_orders')
let number_order = document.getElementById('number_order')
let type = document.getElementById('type')
let coupon_percent = document.getElementById('coupon_percent')
let coupon_price = document.getElementById('coupon_price')
let coupon_price_input = document.getElementById('coupon_price_input')
let coupon_percent_input = document.getElementById('coupon_percent_input')
if(coupon_percent_value != ''){
    if(coupon_percent.classList.contains('display-none')){
        coupon_percent.classList.remove('display-none')
        if(!coupon_percent_input.hasAttribute('required')){
            coupon_percent_input.setAttribute('required', true)
        }
    }
    if(!coupon_price.classList.contains('display-none')){
        coupon_price.classList.add('display-none')
    }
    if(coupon_price_input.hasAttribute('required')){
        coupon_price_input.removeAttribute('required')
    }
}else if(coupon_price_value != ''){
    if(coupon_price.classList.contains('display-none')){
        coupon_price.classList.remove('display-none')
        if(!coupon_price_input.hasAttribute('required')){
            coupon_price_input.setAttribute('required', true)
        }
    }
    if(!coupon_percent.classList.contains('display-none')){
        coupon_percent.classList.add('display-none')
    }
    if(coupon_percent_input.hasAttribute('required')){
        coupon_percent_input.removeAttribute('required')
    }
    console.log(coupon_percent_input.hasAttribute('required'))
}

coupon_type.addEventListener('change', function () {
    if(coupon_type.value == 'percent'){
        if(coupon_percent.classList.contains('display-none')){
            coupon_percent.classList.remove('display-none')
            if(!coupon_percent_input.hasAttribute('required')){
                coupon_percent_input.setAttribute('required', true)
            }
        }
        if(!coupon_price.classList.contains('display-none')){
            coupon_price.classList.add('display-none')
        }
        if(coupon_price_input.hasAttribute('required')){
            coupon_price_input.removeAttribute('required')
        }
        coupon_percent_input.value = ''
    }else if(coupon_type.value == 'price'){
        if(coupon_price.classList.contains('display-none')){
            coupon_price.classList.remove('display-none')
            if(!coupon_price_input.hasAttribute('required')){
                coupon_price_input.setAttribute('required', true)
            }
        }
        if(!coupon_percent.classList.contains('display-none')){
            coupon_percent.classList.add('display-none')
        }
        if(coupon_percent_input.hasAttribute('required')){
            coupon_percent_input.removeAttribute('required')
        }
        coupon_price_input.value = ''
    }
})
type.addEventListener('change', function () {
    if(type.value == 0){
        if(quantity_orders.classList.contains('display-none')){
            quantity_orders.classList.remove('display-none')
        }
        if(!number_order.classList.contains('display-none')){
            number_order.classList.add('display-none')
        }
    }else if(type.value == 1){
        if(number_order.classList.contains('display-none')){
            number_order.classList.remove('display-none')
        }
        if(!quantity_orders.classList.contains('display-none')){
            quantity_orders.classList.add('display-none')
        }
    }
})
