document.addEventListener("DOMContentLoaded", async () => {

    let bestsellerContainer = document.getElementById("best-seller-books");
    let newBooksContainer = document.getElementById("newly-published-books");
    let allBooksContainer = document.getElementById("all-books");

    if(bestsellerContainer) {
        await loadBestSellers();
        wireBookDetailClicks(bestsellerContainer);
        addItemClicks(bestsellerContainer);
        buyItemClicks(bestsellerContainer);
    }
    if(newBooksContainer) {
        await loadNewBooks();
        wireBookDetailClicks(newBooksContainer);
        addItemClicks(newBooksContainer);
        buyItemClicks(newBooksContainer);
    }
    if(allBooksContainer) {
        await loadBooks();
        wireBookDetailClicks(allBooksContainer);
        addItemClicks(allBooksContainer)
        buyItemClicks(allBooksContainer);


    }
    
});

function wireBookDetailClicks(container) {
    container.addEventListener("click", (e) => {
        const el = e.target.closest("[data-id]");
        if (!el) return;
        
        if (e.target.closest("button")) return;
        const editionID = el.getAttribute("data-id");
        if (!editionID) return;
        window.location.href = BASE_URL + `bookDetail/getDetail/${editionID}`;
    });
}

async function loadBestSellers() {

    const container = document.getElementById("best-seller-books");

    try {

        const res = await fetch(BASE_URL + "home/getBestSeller");
        const contentType = res.headers.get("Content-Type") || "";
        if (!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON:", text.slice(0, 200));
            return;
        }
        const data = await res.json();

        if(!data.success) return;

        const books = data.data;

        let html = "";

        books.forEach(book => {

            html += `
            <div class="item">
                <div class="card col-md-9 col-sm-7 col-lg-12 d-flex m-auto flex-column">

                    <div class="image" data-id="${book.editionID}">
                        <img src="${book.imageURL}">
                    </div>

                    <div class="info">
                        <p class="name" data-id="${book.editionID}">${book.volumeName}</p>

                        <div class="price">
                            <p class="quotedPrice">${book.quotedPrice}</p>
                            <p class="salePrice">${book.salePrice}</p>
                        </div>
                    </div>

                    <div class="btn" >
                        <button class="btn btn-addCart" data-id="${book.editionID}">Thêm vào giỏ hàng</button>
                        <button class="btn btn-buyNow" data-id="${book.editionID}">Mua ngay</button>
                    </div>

                </div>
            </div>
            `;

        });

        
        container.innerHTML = html;

        $("#best-seller-books").owlCarousel({
            loop:true,
            margin:10,
            nav:true,
            dots:false,
            responsive:{
                0:{items:1},
                600:{items:3},
                1000:{items:4}
            }
        });

    } catch(err){
        console.error(err);
    }
}

async function loadNewBooks() {
    let newBooksContainer = document.getElementById("newly-published-books");
    try {
        const res = await fetch(BASE_URL + "home/getNewBooks");
        const contentType = res.headers.get("Content-Type") || "";  
        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON:", text.slice(0, 200));
            return;
        }
        const data = await res.json();
        if(data.success) {
            console.log("Danh sách sách mới xuất bản:", data.data);
            const books = data.data;
            
            let html = "";

                books.forEach(book => {

                    

                    html += `
                    <div class="item">
                        <div class="card col-md-9 col-sm-7 col-lg-12 d-flex m-auto flex-column">
                            <div class="image" data-id="${book.editionID}">
                                <img src="${book.imageURL}" class="" >
                            </div>

                            <div class="info">
                                <p class="name" data-id="${book.editionID}">${book.volumeName}</p>

                                <div class="price">
                                    <p class="quotedPrice">${book.quotedPrice}</p>
                                    <p class="salePrice">${book.salePrice}</p>
                                </div>
                            </div>

                            <div class="btn" >
                                <button class="btn btn-addCart" data-id="${book.editionID}">Thêm vào giỏ hàng</button>
                                <button class="btn btn-buyNow" data-id="${book.editionID}">Mua ngay</button>
                            </div>

                        </div>
                    </div>
                    `;

                });
                newBooksContainer.innerHTML = html;
                
                $("#newly-published-books").owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    dots: false,
                    responsive: {
                        0: { items: 1 },
                        600: { items: 3 },
                        1000: { items: 4 }
                    }
                });

            }
        

    } catch(e) {
        console.error("Lỗi tải danh sách sách mới xuất bản:", e);
        alert("Không thể tải danh sách sách mới xuất bản. Vui lòng thử lại sau.");
    }
}

async function loadBooks(page = 1) {
    try {
        const res = await fetch(BASE_URL + `home/getBooks/${page}`);
        const contentType = res.headers.get("Content-Type") || "";
        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON:", text.slice(0, 200));
            return;
        }

        const data = await res.json();
        if(data.success) {
            console.log(data);
            const books = data.data;
            const container = document.getElementById("all-books");
            let html = "";
            books.forEach(book => {

                html += `
                <div class="item">
                        <div class="card col-md-9 col-sm-7 col-lg-12 d-flex m-auto flex-column">
                            <div class="image" data-id="${book.editionID}">
                                <img src="${book.imageURL}" class="">
                            </div>

                            <div class="info">
                                <p class="name" data-id="${book.editionID}">${book.volumeName}</p>

                                <div class="price">
                                    <p class="quotedPrice">${book.quotedPrice}</p>
                                    <p class="salePrice">${book.salePrice}</p>
                                </div>
                            </div>

                            <div class="btn" >
                                <button class="btn btn-addCart" data-id="${book.editionID}">Thêm vào giỏ hàng</button>
                                <button class="btn btn-buyNow" data-id="${book.editionID}">Mua ngay</button>
                            </div>

                        </div>
                    </div>

                `;
            });
            container.innerHTML = html;
            renderPagination(data.pagination.totalPage, data.pagination.currentPage);
        }
    } catch(e) {
        console.error("Lỗi tải danh sách tất cả sách:", e);
        alert("Không thể tải danh sách tất cả sách. Vui lòng thử lại sau.");
    }
}

async function renderPagination(totalPage, currentPage) {
    let html = "";
    console.log("Tổng số trang:", totalPage, "Trang hiện tại:", currentPage);
    for(let i = 1; i <= totalPage; i++){

        if(i === currentPage){
            html += `<button class="active">${i}</button>`;
        }else{
            html += `<button onclick="loadBooks(${i})">${i}</button>`;
        }

    }

    document.getElementById("pagination").innerHTML = html;
}

function addItemClicks(container) {
    container.addEventListener("click", (e) => {
        const btn = e.target.closest(".btn-addCart");
        if(!btn) return;
        const editionID = btn.getAttribute("data-id");
        if(!editionID) return;
        const userID = JSON.parse(localStorage.getItem("userID"));
        if(!userID) {
            alert("Người dùng chưa đăng nhập. Vui lòng đăng nhập đẻ thêm sách vào giỏ hàng!");
            return;
        }
        window.location.href = BASE_URL + `cart/addItem/${editionID}`;
    });
}

function buyItemClicks(container) {
    container.addEventListener("click", (e) => {
        const btn = e.target.closest(".btn-buyNow");
        if(!btn) return;
        const editionID = btn.getAttribute("data-id");
        if(!editionID) return;
        const userID = JSON.parse(localStorage.getItem("userID"));
        if(!userID) {
            alert("Người dùng chưa đăng nhập. Vui lòng đăng nhập đẻ mua hàng!");
            return;
        }
        window.location.href = BASE_URL + `checkout/getEditionCheckout/${editionID}`;
    });
}