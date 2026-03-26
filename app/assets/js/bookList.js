document.addEventListener("DOMContentLoaded", async () => {
    const bookListContainer = document.getElementById("list");
    if (bookListContainer) {
        const { categoryID, page } = getCategoryAndPageFromPath();
        await loadCategories();
        await loadBookList(categoryID, page);
        wireBookDetailClicks(bookListContainer);
    }
})

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

function getCategoryAndPageFromPath() {
    
    const parts = window.location.pathname.replace(/^\/|\/$/g, '').split('/');
    const idx = parts.findIndex(p => p.toLowerCase() === 'booklist');
    const action = idx >= 0 ? (parts[idx + 1] || '') : '';
    const categoryID = idx >= 0 ? (parts[idx + 2] || '') : '';
    const pageStr = idx >= 0 ? (parts[idx + 3] || '1') : '1';
    const page = Math.max(1, parseInt(pageStr, 10) || 1);
    return { action, categoryID, page };
}

async function loadBookList(categoryID, page) {
    const bookListContainer = document.getElementById("list");
    try {
        const res = await fetch(BASE_URL + `bookList/getBooksBy/${categoryID}/${page}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        });
        const contentType = res.headers.get("Content-Type") || "";
        if (!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON:", text.slice(0, 200));
            return;
        }

        const data = await res.json();
        if(data.success) {
            const books = data.data;
            console.log("book: " , books[0]);
            let html = "";
            books.forEach(book => {
                html += `
                    <div class="item" >
                        <div class="card col-md-9 col-sm-7 col-lg-12 d-flex m-auto flex-column">

                            <div class="image" data-id="${book.editionID}">
                                <img src="${BASE_URL}/${book.imageURL}">
                            </div>

                            <div class="info">
                                <p class="name" data-id="${book.editionID}">${book.volumeName}</p>

                                <div class="price">
                                    <p class="quotedPrice">${formatCurrency(parsePriceToInt(book.quotedPrice))}</p>
                                    <p class="salePrice">${formatCurrency(parsePriceToInt(book.salePrice))}</p>
                                </div>
                            </div>

                            <div class="btn">
                                <button class="btn btn-addCart" data-id="${book.editionID}">Thêm vào giỏ hàng</button>
                                <button class="btn btn-buyNow" data-id="${book.editionID}">Mua ngay</button>
                            </div>

                        </div>
                    </div>
                `
            })
            bookListContainer.innerHTML = html;
            addItemClicks(bookListContainer);
            buyItemClicks(bookListContainer);
            renderPagination(data.pagination.totalPage, data.pagination.currentPage, categoryID);
        } else {
            console.error("Lỗi: " + data.message);
        }

    } catch(e) {
        console.error("Lỗi tải danh sách sách:", e);
        alert("Không thể tải danh sách sách. Vui lòng thử lại sau.");
    }

}

function formatCurrency(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "đ";
}

function parsePriceToInt(amount) {
    return parseInt(amount);
}



function renderPagination(totalPage, currentPage, categoryID) {
    let html = "";
    for(let i = 1; i <= totalPage; i++) {
        if(i === currentPage) {
            html += `<button class="active">${i}</button>`;
        } else {
            html += `<button data-page="${i}">${i}</button>`;
        }
    }
    let pagination = document.getElementById("pagination");
    pagination.innerHTML = html;

    pagination.querySelectorAll("button[data-page]").forEach(btn => {
        btn.addEventListener("click", async () => {
            const page = parseInt(btn.getAttribute("data-page"), 10) || 1;
            await loadBookList(categoryID, page);
        });
    });
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

async function loadCategories() {
    try {
        const res = await fetch(BASE_URL + `bookList/categories`);
        const contentType = res.headers.get("Content-Type") || "";
        if (!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON:", text.slice(0, 200));
            return;
        }

        const data = await res.json();
        if(data.success) {
            console.log("categories", data.data);
            const categoryList = document.getElementById("categoryList");
            const categories = data.data;
            let html = "";
            categories.forEach(c => {

                html += `
                    <div>
                        <input type= "checkbox">
                        <p data-id="${c.id}">${c.categoryName}</p>
                    </div>
                `
            });
            categoryList.innerHTML = html;
        } else {
            console.error(data.message);
            alert(data.message);
            return;
        }
    } catch(e) {
        console.error("Error: ", e);

    }
}