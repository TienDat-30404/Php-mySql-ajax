var increaseQuantity = document.querySelectorAll('.increase_quantity');
var decreaseQuantity = document.querySelectorAll('.decrease_quantity')
increaseQuantity.forEach(function(item)
{
    item.addEventListener('click', function(event)
    {
        var inputQuantity = event.target.nextElementSibling;
        inputQuantity.value = parseInt(inputQuantity.value) + 1
        var idProduct = event.target.getAttribute('data-id-product');
        var idUser = document.querySelector('.hidden_idUser').value
        var price_product = event.target.parentNode.parentNode.parentNode.querySelector('.price_product');
        var total_product = event.target.parentNode.parentNode.parentNode.querySelector('.total_price-cart');
        
        total_product.innerText = parseInt(price_product.getAttribute('data-price')) * parseInt(inputQuantity.value);

        var numberPriceProduct = document.querySelectorAll('.total_price-cart')
        var total = 0;
        for(var i = 0; i < numberPriceProduct.length; i++)
        {
            
            total = total + parseInt(numberPriceProduct[i].textContent)
        }
        var totalCartUser = document.querySelector('.total_price-cart-user');
        totalCartUser.innerText = total
        QuantityCart(idProduct, inputQuantity.value, idUser);

    })
})

decreaseQuantity.forEach(function(item)
{
    item.addEventListener('click', function(event)
    {
        var inputQuantity = event.target.previousElementSibling;
        if(inputQuantity.value > 0)
        {
            inputQuantity.value = parseInt(inputQuantity.value) - 1
            var idProduct = event.target.getAttribute('data-id-product');
            var idUser = document.querySelector('.hidden_idUser').value
            var price_product = event.target.parentNode.parentNode.parentNode.querySelector('.price_product');
            var total_product = event.target.parentNode.parentNode.parentNode.querySelector('.total_price-cart');
            total_product.innerText = parseInt(price_product.getAttribute('data-price')) * parseInt(inputQuantity.value);
            var numberPriceProduct = document.querySelectorAll('.total_price-cart')
            var total = 0;
            for(var i = 0; i < numberPriceProduct.length; i++)
            {
                 
                total = total + parseInt(numberPriceProduct[i].textContent)
            }
            console.log(total)
            var totalCartUser = document.querySelector('.total_price-cart-user');
            totalCartUser.innerText = total
            QuantityCart(idProduct, inputQuantity.value, idUser);
        }
        
    })
})
async function QuantityCart(idProduct, quantityProduct, idUser)
{
    var link = await fetch(`frontend/pages/quantity_cart.php?id_product=${idProduct}&quantity=${quantityProduct}&id_user=${idUser}`)
}