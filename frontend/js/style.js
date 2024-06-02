

async function DataDetail(id)
{
    var link = await fetch(`frontend/pages/detail.php?id_detail=${id}`)
    var json = await link.json();
    console.log(json)
    DisplayDetail(json)
}
function DisplayDetail(data)
{
    var value = data.informations;
    if(value.detail == null)
    {
        value.detail = "Hiện chưa có chi tiết sản phẩm"
    }
    if(!value.nameAuthor)
    {
        value.nameAuthor = "Chưa có tác giả"
    }
    if(value.namePublisher == null)
    {
        value.namePublisher = "Chưa có nhà xuất bản"
    }
    var informations = `
        <div class = "modal"> 
            <div class = "modal_base">  
                <div class = "detail">
                    <a class = "detail_exit" href="index.php">X</a>
                    <div class = "detail_img">
                        <img class = "detail_img-css" src="${value.image}" alt="">
                    </div>
                    <div class = "detail_product">
                        <h3 class = "detail_product-name">${value.nameProduct}</h3>
                        <ul class = "detail_product-category">
                            <li>
                                <h4 class = "detail_product-category-title">Thể loại : </h4>
                                <h4 class = "detail_product-category-name story">${value.nameCategory}</h4>
                            </li>
                            <li>
                                <h4 class = "detail_product-category-title">Tên tác giả : </h4>
                                <h4 class = "detail_product-category-name author">${value.nameAuthor}</h4>
                            </li>
                            <li>
                                <h4 class = "detail_product-category-title">Nhà xuất bản : </h4>
                                <h4 style = "max-width : 400px" class = "detail_product-category-name publisher">${value.namePublisher}</h4>
                            </li>
                            <li>
                                <h4 class = "detail_product-category-title">Năm xuất bản : </h4>
                                <h4 class = "detail_product-category-name year">${value.publishYear}</h4>
                            </li>
                            <li>
                                <h4 class = "detail_product-category-title">Mô tả sản phẩm :  </h4>
                                <h4 class = "detail_product-category-name descripbe ">${value.detail}</h4>
                            </li>
                            <li style = "width : 50%">
                                <h4 class = "detail_product-category-title price">${value.price}</h4>
                                <h4 class = "detail_product-category-name year text_price">đ</h4>
                            </li>
                            <li>
                                <h4 class = "detail_product-category-title price text_number">Số lượng</h4>
                                <input type="number">
                            </li>
                            <button class = "detail_button" data-id-product = ${value.id}>Cho vào giỏ</button>
                        </ul>
                    
                    </div>
                </div>
            </div>
        </div>
        `   
    document.querySelector('.detail_block').innerHTML = informations;
    var button = document.querySelectorAll('.detail_button');
    button.forEach(function(item)
    {
        console.log(item)
        item.addEventListener('click', function(event)
        {
            var idProduct = event.target.getAttribute('data-id-product');
            addToCart(idProduct);
        })
    })
    var detailExit = document.querySelector('.detail_exit');
    var modal = document.querySelector('.modal');
    detailExit.addEventListener('click', function(e)
    {
        e.preventDefault();
        modal.style.display = 'none'
    })
}


var currentPage = 1;
var pageSize = 5;
var contentProduct = document.querySelector('.content');
var paginationHomePage = document.querySelector('.pagination_homepage');
async function fetchData(url) {
    var response = await fetch(url);
    return await response.json();
}

async function displayHomePage() { 
    var url = `frontend/pages/product.php?page=${currentPage}&pageSize=${pageSize}`;
    var data = await fetchData(url);
    displayProducts(data, contentProduct);
    displayPagination(data, paginationHomePage, 1);
}
function displayProducts(data, content123) {
    var productsHTML = data.informations.map(value => {
        return `
        <a class = "clickDetail" data-id_detail = ${value.id} >
            <div class="content_product">
                <div class="content_product-information">
                    <img src="${value.image}">
                    <div class="content_product-information-bought">
                        <a data-id_detail = ${value.id} class="content_product-information-bought-name">${value.nameProduct}</a>
                        <div class="content_product-information-bought-price">
                            <h5 class="content_product-information-bought-price-number">${value.price}</h5>
                            <h6 class="content_product-information-bought-price-text">đ</h6>
                        </div>
                        
                    </div>
                    <button data-product-id = ${value.id} class="content_product-information-button button-margin">Cho vào giỏ</button>
                </div>
            </div>
        </a>
    `}).join('');
    content123.innerHTML = productsHTML;    
    var button = document.querySelectorAll('.content_product-information-button');
    button.forEach(function(item)
    {
        item.addEventListener('click', function(event)
        {
            console.log(item)
            var idProduct = event.target.getAttribute('data-product-id');
            addToCart(idProduct);
        })
    })
    var clickDetail = document.querySelectorAll('.clickDetail')
    clickDetail.forEach(function(item)
    {
        item.addEventListener('click', function(e)
    {
        e.preventDefault();
        var id = this.getAttribute('data-id_detail')
        DataDetail(id)
    })
    })

    var nameDetail = document.querySelectorAll('.content_product-information-bought-name')
    nameDetail.forEach(function(item)
    {
        item.addEventListener('click', function(event)
    {
        event.preventDefault();
        var id = this.getAttribute('data-id_detail')
        DataDetail(id);
    })
    })
}
function displayPagination(data, pagination123, page) {
    pagination123.innerHTML = "";
    var maxPage = Math.ceil(data.number/ pageSize);
    var start = 1;
    var end = maxPage;
    if(currentPage > 2 && maxPage > 3 && currentPage < maxPage)
    {
        start = currentPage - 1;
        end = currentPage + 1;
    }
    else if(currentPage == maxPage && maxPage > 3)
    {
        start = currentPage - 2;
        end = maxPage;
    }
    else if(maxPage > 3 && currentPage <= 2)
    {
        end = 3;
    }
    else if(maxPage == 1)
    {
        pagination123.classList.add('hide');
    }
    
    else if(maxPage > 1)
    {
        pagination123.classList.remove('hide')
    }
    if(currentPage > 1)
    {
        var prevPage = document.createElement('li');
        prevPage.innerText = "Prev";
        prevPage.setAttribute('onclick', "ChangePage(" + (currentPage - 1) + ", " + page + ")");
        pagination123.appendChild(prevPage);
    }
    for (var i = start; i <= end; i++) {
        var pageButton = document.createElement('li');
        pageButton.innerText = i;
        if(i == currentPage)
        {
            pageButton.classList.add('headPage')
        }
        pageButton.setAttribute('onclick', "ChangePage(" + (i) + ", " + page + ")" )
        pagination123.appendChild(pageButton);
    }
    if(currentPage < maxPage)
    {
        var nextPage = document.createElement('li')
        nextPage.innerText = "Next";
        nextPage.setAttribute('onclick', "ChangePage(" + (currentPage + 1) + ", " + page + ")");
        pagination123.appendChild(nextPage)
    }
}
function ChangePage(index, pagePagination)
{
    currentPage = index;
    if(pagePagination == 1)
    {
        displayHomePage(currentPage);
    }
    else if(pagePagination == 2)
    {
        displayCategory(idCategory)
    }
    else if(pagePagination == 3)
    {
        displaySearch(currentPage)
    }
}
displayHomePage(currentPage);


var contentCategory = document.querySelector('.content_category');

var paginationCategory = document.querySelector('.pagination_category');
async function displayCategory(idCategory) {
    var url = `frontend/pages/handle_category.php?page=${currentPage}&pageSize=${pageSize}&id_category=${idCategory}`;
    var data = await fetchData(url);
    console.log(data)
    displayProducts(data, contentCategory);
    displayPagination(data, paginationCategory, 2);
}
var clickCategory = document.querySelectorAll('.clickCategory')
clickCategory.forEach(function(item)
{
    item.addEventListener('click', function(e)
    {
        idCategory = this.getAttribute('data-id-category');
        currentPage = 1;
        contentProduct.style.display = 'none'
        displayCategory(idCategory)
        paginationHomePage.style.display = 'none'
    })
})


var contentSearch = document.querySelector('.content_search');
var paginationSearch = document.querySelector('.pagination_search');
async function displaySearch() {
    
    var urlParams = new URLSearchParams(window.location.search)
    var nameSearch = urlParams.get('inputSearchName');
    
    var url = `frontend/pages/search.php?page=${currentPage}&pageSize=${pageSize}&inputSearchName=${nameSearch}`;
    var data = await fetchData(url);
    displayProducts(data, contentSearch);
    displayPagination(data, paginationSearch, 3);
}
 
displaySearch(currentPage)





var headerSearchBlock = document.querySelector('.header_search-block')
headerSearchBlock.style.display = 'none'
var iconAdvanced = document.querySelector('.icon_advanced')
iconAdvanced.addEventListener('click', function()
{
    if(headerSearchBlock.style.display ==='none')
    {
        headerSearchBlock.style.display = 'block';
    }
    else 
    {
        headerSearchBlock.style.display = 'none';
    }
})
document.addEventListener('click', function(event) {
    if (!event.target.closest('.icon_advanced') && !event.target.closest('.header_search-block')) {
        headerSearchBlock.style.display = 'none';
    }
});


var contentSearchAdvanced = document.querySelector('.content_searchAdvanced')
var paginationSearchAdvanced = document.querySelector('.pagination_searchAdvanced')
var nameSearchAdvanced;
var categorySearchAdvanced;
var priceFrom;
var priceTo;
async function SearchAdvanced(nameSearch, categorySearch, priceFrom, priceTo)
{
    var link = await fetch(`frontend/pages/search_advanced.php?page=${currentPage}&pageSize=${pageSize}&nameSearchAdvanced=${nameSearch}&search_select=${categorySearch}
    &priceFrom=${priceFrom}&priceTo=${priceTo}`)
   var json =  await link.json();
   DisplaySearchAdvanced(json)
   PaginationSearchAdvanced(json)

}

document.querySelector('.header_search-advanced-submit').addEventListener('click', function(event)
{
    event.preventDefault();
    headerSearchBlock.style.display = 'block'
    document.querySelector('.searchAdvanced_block').style.display = 'block';
    paginationSearchAdvanced.innerHTML = "";
    nameSearchAdvanced = document.querySelector('.header_search-advanced-name-input').value;
    categorySearchAdvanced = document.querySelector('.header_search-advanced-divide-select').value;
    priceFrom = document.querySelector('.header_search-advanced-price-from-input').value;
    priceTo = document.querySelector('.header_search-advanced-price-to-input').value
    if(isNaN(priceFrom) || isNaN(priceTo))
    {
        alert("Giá nhập vào phải là số")
        if(isNaN(priceFrom))
        {
            document.querySelector('.header_search-advanced-price-from-input').focus();
        }
        else if(isNaN(priceTo))
        {
            document.querySelector('.header_search-advanced-price-to-input').focus();
        }
    }
    currentPage = 1;
    var urlParams = new URLSearchParams(window.location.search)
    if(urlParams.get('nameSearch') && urlParams.get('inputSearchName'))
    {
        contentSearch.style.display = 'none'
        paginationSearch.style.display = 'none'
    }
    SearchAdvanced(nameSearchAdvanced, categorySearchAdvanced, priceFrom, priceTo);
})

function DisplaySearchAdvanced(data)
{
    var informations = data.informations.map(value => 
        `
        <a class = "clickDetail" data-id_detail = ${value.id} >
            <div class="content_product">
                <div class="content_product-information">
                    <img src="${value.image}">
                    <div class="content_product-information-bought">
                        <a data-id_detail = ${value.id} class="content_product-information-bought-name">${value.nameProduct}</a>
                        <div class="content_product-information-bought-price">
                            <h5 class="content_product-information-bought-price-number">${value.price}</h5>
                            <h6 class="content_product-information-bought-price-text">đ</h6>
                        </div>
                    </div>
                    <button class="content_product-information-button button-margin">Cho vào giỏ</button>
                </div>
            </div> 
        </a>
        `
    )
    contentSearchAdvanced.innerHTML = informations;
    // content.innerHTML = productsHTML;
    var clickDetail = document.querySelectorAll('.clickDetail')
    clickDetail.forEach(function(item)
    {
        item.addEventListener('click', function(e)
    {
        e.preventDefault();
        console.log("123")
        var id = this.getAttribute('data-id_detail')
        console.log(id)
        DataDetail(id)
    })
    })

    var nameDetail = document.querySelectorAll('.content_product-information-bought-name')
    nameDetail.forEach(function(item)
    {
        item.addEventListener('click', function(event)
    {
        event.preventDefault();
        var id = this.getAttribute('data-id_detail')
        console.log(id)
        DataDetail(id);
    })
    })
}
function PaginationSearchAdvanced(data)
{
    if(data.number == 0)
    {
        paginationSearchAdvanced.innerHTML = "";
    }
    else 
    {

        paginationSearchAdvanced.innerHTML = "";
        var maxPage = Math.ceil(data.number / pageSize)
        var start = 1;
        var end = maxPage;
        if(currentPage > 2 && currentPage < maxPage && maxPage > 3)
        {
            start = currentPage - 1;
            end = currentPage + 1;
        }
        else if(currentPage == maxPage && maxPage > 3)
        {
            start = currentPage - 2;
            end = maxPage;
        }
        else if(maxPage == 1)
        {
            document.querySelector('.searchAdvanced_block').style.display = 'none';
            paginationSearchAdvanced.classList.add('hide')
    
        }
        else if(currentPage <= 2 && maxPage > 3)
        {
            end = 3;
        }
        if(currentPage > 1)
        {
            var prevPage = document.createElement('li')
            prevPage.innerText = "Prev";
            prevPage.setAttribute('onclick', "ChangeSearchAdvanced("+ (currentPage - 1) +")")
            paginationSearchAdvanced.appendChild(prevPage)
        }
        for(var i = start; i <= end; i++)
        {
            var newPage = document.createElement('li')
            newPage.innerText = i;
            if(i == currentPage)
            {
                newPage.classList.add('headPage')
            }
            newPage.setAttribute('onclick', "ChangeSearchAdvanced("+ (i) +")")
            paginationSearchAdvanced.appendChild(newPage)
        }
        if(currentPage < maxPage)
        {
            var nextPage = document.createElement('li')
            nextPage.innerText = "Next";
            nextPage.setAttribute('onclick', "ChangeSearchAdvanced("+ (currentPage + 1) +")")
            paginationSearchAdvanced.appendChild(nextPage)
        }
    }
}
function ChangeSearchAdvanced(index)
{
    currentPage = index;
    SearchAdvanced(nameSearchAdvanced, categorySearchAdvanced, priceFrom, priceTo)
}

async function addToCart(idProduct)
{
    var link = await fetch(`frontend/pages/add_cart.php?id_product=${idProduct}`)
    var json = await link.json();
    var fail = "Sản phẩm đã được thêm vào giỏ hàng";
    if(json.status === fail)
    {
        toast_fail(fail);
    }
}



// Sign Up
var fullname;
var email
var password
var password_confirmation
async function SignUp(fullname, email, password, password_confirmation)
{
    var link = await fetch(`frontend/pages/signup/handle_signup.php?fullname=${fullname}&email=${email}&password=${password}
    &password_confirmation=${password_confirmation}`);
    var text = await link.text();
    var fail = "Tên đăng nhập hoặc tài khoản đã tồn tại";
    var success = "Đăng kí thành công";
    if(text === fail)
    {
        toast_fail(text)
    }
    else 
    {
        toast_success(success);
    }
}

var buttonSignUp = document.querySelector('.button_signup')
document.querySelector('.button_signup').addEventListener('click', function(event)
{
    event.preventDefault()
    fullname = document.getElementById('fullname').value;
    var checkEmail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2, 3})+$/;
    email = document.getElementById('email').value;
    password = document.querySelector('#password').value;
    password_confirmation = document.querySelector('#password_confirmation').value
    if(fullname == "" || email == "" || password <= 2 || password == "" || password != password_confirmation)
    {
        if(fullname == "")
        {
            var borderMessage = document.querySelector('#fullname');
            var notificationMessage = borderMessage.nextElementSibling;
            notificationMessage.innerText = "Vui lòng nhập vào trường này"
            borderMessage.classList.add('border-message')
        }   
        else 
        {
            var borderMessage = document.querySelector('#fullname');
            var notificationMessage = borderMessage.nextElementSibling;
            notificationMessage.innerText = ""
            borderMessage.classList.remove('border-message')
        }
   
        if(!checkEmail.test(email) || email == "")
        {
            if(email == "")
            {
                var borderMessage = document.querySelector('#email');
                var notificationMessage = borderMessage.nextElementSibling;
                notificationMessage.innerText = "Email không được rỗng"
                borderMessage.classList.add('border-message')
            }
            else if(email != "" && !checkEmail.test(email))
            {
                var borderMessage = document.querySelector('#email');
                var notificationMessage = borderMessage.nextElementSibling;
                notificationMessage.innerText = "Email không hợp lệ"
                borderMessage.classList.add('border-message')
            }
        }
        else 
        { 
            var borderMessage = document.querySelector('#email');
            var notificationMessage = borderMessage.nextElementSibling;
            notificationMessage.innerText = ""
            borderMessage.classList.remove('border-message')
        }
        if(password.length <= 2)
        {
            var borderMessage = document.querySelector('#password');
            var notificationMessage = borderMessage.nextElementSibling;
            notificationMessage.innerText = "Mật khẩu tối thiểu 3 chữ số"
            borderMessage.classList.add('border-message')
        }
        else 
        {
            var borderMessage = document.querySelector('#password');
            var notificationMessage = borderMessage.nextElementSibling;
            notificationMessage.innerText = ""
            borderMessage.classList.remove('border-message')
        }
        if(password == "" || password_confirmation == "" || password != password_confirmation)
        {
            if(password == "")
            {
                var borderMessage = document.querySelector('#password');
                var notificationMessage = borderMessage.nextElementSibling;
                notificationMessage.innerText = "Vui lòng nhập mật khẩu "
                borderMessage.classList.add('border-message')
            }
            if(password_confirmation == "")
            {
                var borderMessage = document.querySelector('#password_confirmation');
                var notificationMessage = borderMessage.nextElementSibling;
                notificationMessage.innerText = "Vui lòng nhập vào trường này"
                borderMessage.classList.add('border-message')
            }
            if(password != password_confirmation && password != "" && password_confirmation != "")
            {
                var borderMessage = document.querySelector('#password_confirmation');
                var notificationMessage = borderMessage.nextElementSibling;
                notificationMessage.innerText = "Mật khẩu không trùng khớp"
                borderMessage.classList.add('border-message')
            }
        }
        else 
        {
            var borderMessage = document.querySelector('#password_confirmation');
            var notificationMessage = borderMessage.nextElementSibling;
            notificationMessage.innerText = ""
            borderMessage.classList.remove('border-message')
        }
    }
    else 
    {
        var formGroups = document.querySelectorAll('.form-group')
        for(var i = 0; i < formGroups.length; i++)
        {
            var formGroup = formGroups[i];
            var inputFromGroup = formGroup.querySelector('input');
            var spanFromGroup = formGroup.querySelector('.form-message');
            inputFromGroup.classList.remove('border-message')
            spanFromGroup.innerText = "";
        }
        SignUp(fullname, email, password, password_confirmation)
    }
})


function toast_success(message)
    {
        var toast = document.querySelector('.toast')
        var added = document.createElement('div')
        added.classList.add('added')
        added.innerHTML = `
            <div class = "added__check">
                <i class="fa-solid fa-check added__check-icon"></i>
            </div>
            <div class = "added__text">
                <h5 class = "added__text-content">${message}</h5>
            </div>
            <div class = "added__exit">
                <i class="fa-solid fa-xmark added__exit-icon"></i>
            </div>
        `
        toast.append(added)
        setTimeout(function()
        {
            toast.removeChild(added)
        }, 2500)
}


function toast_fail(text)
    {
        var toast = document.querySelector('.toast_fail')
        var added = document.createElement('div')
        added.classList.add('added_fail')
        added.innerHTML = `
            <div class = "added__check">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <div class = "added__text">
                <h5 class = "added__text-content">${text}</h5>
            </div>
            <div class = "added__exit">
                <i class="fa-solid fa-xmark added__exit-icon"></i>
            </div>
        `
        toast.append(added)
        setTimeout(function()
        {
            toast.removeChild(added)
        }, 2500)
}


var iconAdvanced = document.querySelector('.icon_advanced');
var headerSearchBlock = document.querySelector('.header_search-block');
var hiddenContent3 = document.querySelector('.hidden_content-3')
headerSearchBlock.style.display = 'none';

iconAdvanced.addEventListener('click', function(e) {
    if (hiddenContent3.style.display === 'none') {
        hiddenContent3.style.display = 'block';
        headerSearchBlock.style.display = 'block'
        document.querySelector('.searchAdvanced_block').style.display = 'block'
    } else {
        hiddenContent3.style.display = 'none';
        headerSearchBlock.style.display = 'none'
        document.querySelector('.searchAdvanced_block').style.display = 'none'
    }
});