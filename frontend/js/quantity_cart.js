
function CrudCart()
{
    var increaseQuantity = document.querySelectorAll('.increase_quantity');
    var decreaseQuantity = document.querySelectorAll('.decrease_quantity')
    increaseQuantity.forEach(function (item) {
        item.addEventListener('click', function (event) {
            var inputQuantity = event.target.nextElementSibling;
            inputQuantity.value = parseInt(inputQuantity.value) + 1
            var idProduct = event.target.getAttribute('data-id-product');
            var idUser = document.querySelector('.hidden_idUser').value
            var price_product = event.target.parentNode.parentNode.parentNode.querySelector('.price_product');
            var total_product = event.target.parentNode.parentNode.parentNode.querySelector('.total_price-cart');
    
            total_product.innerText = parseInt(price_product.getAttribute('data-price')) * parseInt(inputQuantity.value);
    
            var numberPriceProduct = document.querySelectorAll('.total_price-cart')
            var total = 0;
            for (var i = 0; i < numberPriceProduct.length; i++) {
    
                total = total + parseInt(numberPriceProduct[i].textContent)
            }
            var totalCartUser = document.querySelector('.total_price-cart-user');
            totalCartUser.innerText = total
            QuantityCart(idProduct, inputQuantity.value, idUser);
    
        })
    })
    
    decreaseQuantity.forEach(function (item) {
        item.addEventListener('click', function (event) {
            var inputQuantity = event.target.previousElementSibling;
            if (inputQuantity.value > 0) {
                inputQuantity.value = parseInt(inputQuantity.value) - 1
                var idProduct = event.target.getAttribute('data-id-product');
                var idUser = document.querySelector('.hidden_idUser').value
                var price_product = event.target.parentNode.parentNode.parentNode.querySelector('.price_product');
                var total_product = event.target.parentNode.parentNode.parentNode.querySelector('.total_price-cart');
                total_product.innerText = parseInt(price_product.getAttribute('data-price')) * parseInt(inputQuantity.value);
                var numberPriceProduct = document.querySelectorAll('.total_price-cart')
                var total = 0;
                for (var i = 0; i < numberPriceProduct.length; i++) {
    
                    total = total + parseInt(numberPriceProduct[i].textContent)
                }
                console.log(total)
                var totalCartUser = document.querySelector('.total_price-cart-user');
                totalCartUser.innerText = total
                QuantityCart(idProduct, inputQuantity.value, idUser);
            }
    
        })
    })

    var deleteCart = document.querySelectorAll('.delete_cart')
    deleteCart.forEach(function (item) {
        item.addEventListener('click', function (event) {
            event.preventDefault();
            var idCartProduct = this.getAttribute('data-id-cart')
            var idUser = document.querySelector('.hidden_idUser').value
            DeleteCart(idCartProduct, idUser)
        })
    })
}
async function QuantityCart(idProduct, quantityProduct, idUser) {
    var formData = new FormData();
    formData.append('choice', 'quantity_cart')
    formData.append('id_product', idProduct)
    formData.append('quantity', quantityProduct)
    formData.append('id_user', idUser)
    var link = await fetch(`backend/crud/cart_api.php`, {
        method : 'POST',
        body : formData
    })
    UpdateLoadCart()
}

async function DeleteCart(idCart, idUser) {
    var formData = new FormData();
    formData.append('choice', 'delete_cart')
    formData.append('id_cart', idCart)
    formData.append('id_user', idUser)
    var link = await fetch('backend/crud/cart_api.php', {
        method: 'POST',
        body: formData
    })
    UpdateLoadCart()
}



// click cart 

async function HandleDisplayCart()
{
    var formData = new FormData()
    formData.append('choice', 'display_cart')
    var link = await fetch('backend/crud/cart_api.php', {
        method : 'POST',
        body : formData
    })
    var json = await link.json();
    console.log(json)
    DisplayCart(json)
}



function DisplayCart(data) {
    var informationCart = `
    <div class = "modal"> 
        <div class = "modal_base">  
            <div class = "cart">
                <a class = "detail_exit" href="index.php">X</a>
                <table cellpadding = "10" cellspacing = "0">
                    `
                    if(!data.status)
                    {
                        var idUser = data.idUser
                        var nameUser = data.nameUser
                        informationCart += `
                        <thead>
                            <tr >
                                <th style = "color : blue; font-size : 18px" colspan = "7" >Giỏ hảng : 
                                    <span style = "color : red; padding-left : 10px; font-size : 20px">${nameUser}</span>  
                                </th> 
                            </tr>
                            <tr>
                                <th>STT</th>
                                <th>Ảnh sản phẩm</th>
                                <th>Tên sản phẩm</th>
                                <th>Số lượng</th>
                                <th>Giá sản phẩm</th>
                                <th>Thành tiền</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>   
                            <input class = "hidden_idUser" type="hidden" value = "${idUser}" >
                            `
                            var number = 1
                            var totalPrice = 0
                            data.informations.forEach(function(value)
                            {
                            informationCart += ` 
                                <tr>
                                    <td>${number}</td>
                                    <td><img src="${value.image}" alt=""></td>
                                    <td>${value.name}</td>
                                    <td style = "width : 150px">
                                        <div class = "parent_quantity">
                                            <span class = "increase_quantity" data-id-product = "${value.id}" >+</span>
                                            <input class = "input_quantity" type="text" value = "${value.cart_quantity}">
                                            <span class = "decrease_quantity" data-id-product = "${value.id}">-</span>
                                        </div>
                                    </td>

                                    <td class = "price_product" data-price = ${value.price}>${value.price}</td>
                                    <td class = "total_price-cart">${value.price * value.cart_quantity}</td>
                                    <td>
                                        <a class = "delete_cart" data-id-cart = "${value.id}" href="">Xóa</a>
                                    </td>
                                </tr>
                                
                            `
                            number++
                            totalPrice = totalPrice + value.price * value.cart_quantity
                            console.log(totalPrice)
                            })
                            if(data.informations.length > 0)
                            {
                                
                                informationCart += `         
                                <tr>
                                    <td style = "font-size : 20px; font-family: Roboto, sans-serif;"  colspan = "7">
                                        <div class = "payment_cart-total">
                                            <h5>Tổng tiền</h5>
                                            <span class = "total_price-cart-user">${totalPrice}</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan = "7">
                                        <div class = "payment_cart-text">
                                            <a href ="payment.php">Thanh toán</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                            `
                            }
                    }
                    else {
                        informationCart += ` 
                            <h2 class = "notification_cart">Vui lòng đăng nhập để xem giỏ hàng của bạn để tiến hành thanh toán</h2>
                            <img class = "cart_empty" src="frontend/image/cart_empty.jpg" alt="">
                        `
                    }
                    informationCart += `
                </table>
            </div>
        </div>
    </div>
    `
    document.body.insertAdjacentHTML('beforeend', informationCart);
    CrudCart()
}


var clickCart = document.querySelector('.element_cart')
clickCart.addEventListener('click', function (event) {
    event.preventDefault();
    HandleDisplayCart()
})



async function UpdateLoadCart()
{
    var formData = new FormData()
    formData.append('choice', 'display_cart')
    var link = await fetch('backend/crud/cart_api.php', {
        method : 'POST',
        body : formData
    })
    var json = await link.json();
    LoadCart(json)
}


function LoadCart(data)
{
    var tableBody = document.querySelector('table tbody')
    tableBody.innerHTML = ''
    var number = 1
    var idUser = data.idUser
    var totalPrice = 0
    data.informations.forEach(function(value)
    {
        var row = document.createElement('tr')
        row.innerHTML = 
        `
            <input class = "hidden_idUser" type="hidden" value = "${idUser}" >
            <tr>
                <td>${number}</td>
                <td><img src="${value.image}" alt=""></td>
                <td>${value.name}</td>
                <td style = "width : 150px">
                    <div class = "parent_quantity">
                        <span class = "increase_quantity" data-id-product = "${value.id}" >+</span>
                        <input class = "input_quantity" type="text" value = "${value.cart_quantity}">
                        <span class = "decrease_quantity" data-id-product = "${value.id}">-</span>
                    </div>
                </td>

                <td class = "price_product" data-price = ${value.price}>${value.price}</td>
                <td class = "total_price-cart">${value.price * value.cart_quantity}</td>
                <td>
                    <a class = "delete_cart" data-id-cart = "${value.id}" href="">Xóa</a>
                </td>
            </tr>
        `;
        number++
        totalPrice = totalPrice + value.price * value.cart_quantity
        tableBody.appendChild(row);
        })
        var rowTotal = document.createElement('tr')
        rowTotal.innerHTML = `
            <tr>
                <td style = "font-size : 20px; font-family: Roboto, sans-serif;"  colspan = "7">
                    <div class = "payment_cart-total">
                        <h5>Tổng tiền</h5>
                        <span class = "total_price-cart-user">${totalPrice}</span>
                    </div>
                </td>
            </tr>
        
        `
        tableBody.appendChild(rowTotal)

        var rowPayment = document.createElement('tr')
        rowPayment.innerHTML = `
            <tr>
                <td colspan = "7">
                    <div class = "payment_cart-text">
                        <a href ="payment.php">Thanh toán</a>
                    </div>
                </td>
            </tr>
        `
        tableBody.appendChild(rowPayment)
    CrudCart()
}