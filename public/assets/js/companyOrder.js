let product_name = document.getElementById('product_name')
let order_size = document.getElementById('order_size')
let order_color = document.getElementById('order_color')
let remaining_quantity = document.getElementById('remaining_quantity')
let order_quantity = document.getElementById('order_quantity')
let products_images = document.getElementById('products_images')
let carousel_product_images = document.getElementById('carousel_product_images')
let product_image_content = document.getElementById('product_image_content')
let carousel_product_upload_images = document.getElementById('carousel_product_upload_images')
let product_image = document.getElementById('product_image')
let cancell_order = document.getElementById('cancell_order')
let perform_order = document.getElementById('perform_order_')
let accepted_by_recipient_order = document.getElementById('accepted_by_recipient_order')
let order_delivered_ = document.getElementById('order_delivered')
let ready_for_pick_up_ = document.getElementById('ready_for_pick_up_')
let cancell_accepted_by_recipient_order = document.getElementById('cancell_accepted_by_recipient_order')
let cancell_order_delivered_ = document.getElementById('cancell_order_delivered')
let cancell_ready_for_pick_up_ = document.getElementById('cancell_ready_for_pick_up_')
let perform_order_button = document.getElementById('perform_order_button')
let accordion_arrow = document.getElementsByClassName('accordion_arrow')
let custom_accordion_title = document.getElementsByClassName('custom-accordion-title')

function accepting_order(quantity, remaining_quantity_, color_name, size_name, product_name_, image, image_1, url){
    product_name.innerHTML = ""
    order_size.innerHTML = ""
    order_color.innerHTML = ""
    remaining_quantity.innerHTML = ""
    order_quantity.innerHTML = ""
    product_image.innerHTML = ""
    if(product_name_ != ""){
        product_name.innerHTML = "<h5>"+product_name_text+" "+product_name_+"</h5>"
    }
    if(size_name != ""){
        order_size.innerHTML = "<span>"+size_text+" <b>"+size_name+"</b></span>"
    }
    if(color_name != ""){
        order_color.innerHTML = "<span>"+order_color_text+" <b>"+color_name+"</b></span>"
    }
    if(remaining_quantity_ != "" && parseInt(remaining_quantity_) > 0){
        remaining_quantity.innerHTML = "<span>"+remaining_in_warehouse_text+" <b>"+remaining_quantity_+"</b></span>"
        perform_order_button.disabled = false
    }else{
        remaining_quantity.innerHTML = "<span>"+remaining_in_warehouse_text+ "<span class='badge badge-outline-warning'>"+out_of_stock_text+"</span>";
        perform_order_button.disabled = true
    }
    if(quantity != ""){
        order_quantity.innerHTML = "<span>"+order_quantity_text+" <b>"+quantity+"</b></span>"
    }
    if(image != "" && image_1 != ""){
        product_image.innerHTML = "<img height='64px' src='"+image+"'>" +
            "<img height='64px' src='"+image_1+"'>"
    }

    if(url){
        perform_order.setAttribute("action", url)
    }else{
        perform_order.setAttribute("action", "")
    }
}
function accepted_by_recipient(url){
    if(url){
        accepted_by_recipient_order.setAttribute("action", url)
    }else{
        accepted_by_recipient_order.setAttribute("action", "")
    }
}
function order_delivered(url){
    if(url){
        order_delivered_.setAttribute("action", url)
    }else{
        order_delivered_.setAttribute("action", "")
    }
}
function ready_for_pick_up(url){
    if(url){
        ready_for_pick_up_.setAttribute("action", url)
    }else{
        ready_for_pick_up_.setAttribute("action", "")
    }
}
function cancell_accepted_by_recipient(url){
    cancell_accepted_by_recipient_order.setAttribute("action", url)
}
function cancell_ready_for_pick_up(url){
    cancell_ready_for_pick_up_.setAttribute("action", url)
}
function cancell_order_delivered(url){
    cancell_order_delivered_.setAttribute("action", url)
}
function accepting_anime_order(quantity, product_name_, size_name, color_name, images_0, images_1, url){
    product_name.innerHTML = ""
    order_size.innerHTML = ""
    order_color.innerHTML = ""
    remaining_quantity.innerHTML = ""
    order_quantity.innerHTML = ""
    product_image.innerHTML = ""
    if(product_name_ != ""){
        product_name.innerHTML = "<h5>"+product_name_text+" "+product_name_+"</h5>"
    }
    if(size_name != ""){
        order_size.innerHTML = "<span>"+size_text+" <b>"+size_name+"</b></span>"
    }
    if(color_name != ""){
        order_color.innerHTML = "<span>"+order_color_text+" <b>"+color_name+"</b></span>"
    }
    if(quantity != ""){
        order_quantity.innerHTML = "<span>"+order_quantity_text+" <b>"+quantity+"</b></span>"
    }
    if(images_0 != "" && images_1 != ""){
        product_image.innerHTML = "<img height='64px' src='"+images_0+"'>" +
            "<img height='64px' src='"+images_1+"'>"
    }
    perform_order_button.disabled = false

    perform_order.setAttribute("action", url)
}
function cancelling_order(order_detail_id){
    cancell_order.setAttribute("action", order_detail_id)
}
function getImages(images) {
    let all_images = images.split(' ')
    let images_content = ''
    for(let i=0; i<all_images.length; i++){
        if(i == 0){
            images_content = images_content +
                `<div class="carousel-item active">
                        <img class="d-block img-fluid" src="${all_images[i]}" alt="First slide">
                    </div>`
        }else{
            images_content = images_content +
                `<div class="carousel-item">
                        <img class="d-block img-fluid" src="${all_images[i]}" alt="First slide">
                    </div>`
        }
    }
    carousel_product_images.innerHTML = images_content
}
function showImage(image) {
    console.log(image)
    let images_content =
                `<div class="carousel-item active">
                        <img class="d-block img-fluid" src="${image}" alt="no image">
                    </div>`
    product_image_content.innerHTML = images_content
}
function getUploads(images) {
    let all_uploads = images.split(' ')
    console.log(all_uploads)
    let uploads_content = ''
    for(let i=0; i<all_uploads.length; i++){
        if(all_uploads[i] != ''){
            if(i == 0){
                uploads_content = uploads_content +
                    `<div class="carousel-item active">
                        <img class="d-block img-fluid" src="${all_uploads[i]}" alt="First slide">
                    </div>`
            }else{
                uploads_content = uploads_content +
                    `<div class="carousel-item">
                        <img class="d-block img-fluid" src="${all_uploads[i]}" alt="First slide">
                    </div>`
            }
        }
    }
    if(uploads_content != ''){
        carousel_product_upload_images.innerHTML = uploads_content
    }
}

for(let i = 0; i< custom_accordion_title.length; i++){
    custom_accordion_title[i].addEventListener("click", function() {
        accordionArrow(i);
    });
}


function accordionArrow(index) {
    for(let j = 0; j< accordion_arrow.length; j++){
        let custom_accordion_title_href = custom_accordion_title[j].getAttribute('href')
        if(custom_accordion_title_href == '#collapseNine'+(index+1)){
            if(accordion_arrow[j].classList.contains('mdi-chevron-down')){
                accordion_arrow[j].classList.remove('mdi-chevron-down')
                accordion_arrow[j].classList.add('mdi-chevron-up')
            }else if(accordion_arrow[j].classList.contains('mdi-chevron-up')){
                accordion_arrow[j].classList.remove('mdi-chevron-up')
                accordion_arrow[j].classList.add('mdi-chevron-down')
            }
        }
    }

}
