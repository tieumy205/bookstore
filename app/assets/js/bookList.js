
document.addEventListener("DOMContentLoaded", async () => {
    const bookListContainer = document.getElementById("list");
    const filterBtn = document.querySelector(".btn-filter");
    const sortSelect = document.getElementById("sortSelect");
    if(bookListContainer) {
        let currentParams = {
            search: "",
            categoryID: "",
            minPrice: "",
            maxPrice: "",
            sort: "newest",
            page: 1
        };
        const params = getCurrentParams();
        currentParams = { ...currentParams, ...params };
        
        if (filterBtn) {
            filterBtn.textContent = "Lọc";
            filterBtn.addEventListener("click", (e) => {
                e.preventDefault();
                applyFilter(currentParams);
            });
        }

        if (sortSelect) {
            sortSelect.value = currentParams.sort || "newest";
            sortSelect.addEventListener("change", (e) => {
                currentParams.sort = e.target.value;
                currentParams.page = 1;
                updateURL(currentParams);
                loadBooks(currentParams);
            });
        }

        bindListActions(bookListContainer);
        await getCategories(currentParams);
        await loadBooks(currentParams);
    }
    
});

function bindListActions(container) {
    container.addEventListener("click", (e) => {
        const addBtn = e.target.closest(".btn-addCart");
        if (addBtn) {
            const editionID = addBtn.getAttribute("data-id");
            if(!editionID) return;
            const userID = JSON.parse(localStorage.getItem("userID"));
            if(!userID) {
                alert("Người dùng chưa đăng nhập. Vui lòng đăng nhập để thêm sách vào giỏ hàng!");
                return;
            }
            window.location.href = BASE_URL + `cart/addItem/${editionID}`;
            return;
        }

        const buyBtn = e.target.closest(".btn-buyNow");
        if (buyBtn) {
            const editionID = buyBtn.getAttribute("data-id");
            if(!editionID) return;
            const userID = JSON.parse(localStorage.getItem("userID"));
            if(!userID) {
                alert("Người dùng chưa đăng nhập. Vui lòng đăng nhập để mua hàng!");
                return;
            }
            window.location.href = BASE_URL + `checkout/getEditionCheckout/${editionID}`;
            return;
        }

        const el = e.target.closest("[data-id]");
        if (!el) return;
        if (e.target.closest("button")) return;
        const editionID = el.getAttribute("data-id");
        if (!editionID) return;
        window.location.href = BASE_URL + `bookDetail/getDetail/${editionID}`;
    });
}

function getCurrentParams() {
    const searchParams = new URLSearchParams(window.location.search);
    if ([...searchParams.keys()].length > 0) {
        return {
            search: searchParams.get("search") || "",
            categoryID: searchParams.get("categoryID") || "",
            minPrice: searchParams.get("minPrice") || "",
            maxPrice: searchParams.get("maxPrice") || "",
            sort: searchParams.get("sort") || "newest",
            page: parseInt(searchParams.get("page"), 10) || 1
        };
    }

    // Backward-compatible: support old URL styles if any still linked.
    return getFromPath();
}

function getFromPath() {
    const parts = window.location.pathname.replace(/^\/|\/$/g, '').split('/');
    const idx = parts.findIndex(p => p.toLowerCase() === 'booklist');

    const action = parts[idx + 1] || "";

    if(action === "getBooks"){
        return {
            search: "",
            categoryID: parts[idx + 2] || "",
            minPrice: "",
            maxPrice: "",
            sort: "newest",
            page: parseInt(parts[idx + 3]) || 1
        };
    }

    if(action === "search"){
        return {
            search: decodeURIComponent(parts[idx + 2] || ""),
            categoryID: "",
            minPrice: "",
            maxPrice: "",
            sort: "newest",
            page: 1
        };
    }

    return {};
}

async function loadBooks(params) {
    try{
        const query = new URLSearchParams(params).toString();

        const res = await fetch(BASE_URL + `bookList/getBooks?${query}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        });

        const data = await res.json();

        if(!data.success) {
            console.log(data.message);
            return;
        };
        const bookListContainer = document.getElementById("list");
        const books = Array.isArray(data.data) ? data.data : [];
        renderBooks(books);
        updateResultCount(books.length);
        renderPagination(data.pagination.totalPage, data.pagination.currentPage, params);
    } catch(e) {
        console.error("Lỗi tải danh sách sách:", e);
        alert("Không thể tải danh sách sách. Vui lòng thử lại sau.");
    }
    
}

function renderBooks(books){
    const container = document.getElementById("list");

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
        `;
    });

    container.innerHTML = html;
}

function updateResultCount(count) {
    const resultCount = document.getElementById("resultCount");
    if (resultCount) {
        resultCount.textContent = String(count);
    }
}

function applyFilter(currentParams){

    currentParams.minPrice = document.getElementById("minPrice").value;
    currentParams.maxPrice = document.getElementById("maxPrice").value;
    currentParams.page = 1;

    updateURL(currentParams);
    loadBooks(currentParams);
}



function formatCurrency(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "đ";
}

function parsePriceToInt(amount) {
    return parseInt(amount);
}



function renderPagination(totalPage, currentPage, currentParams){
    let html = "";

    const start = Math.max(1, currentPage - 2);
    const end = Math.min(totalPage, currentPage + 2);

    for(let i = start; i <= end; i++){
        html += `
            <button class="${i === currentPage ? 'active' : ''}" data-page="${i}">
                ${i}
            </button>
        `;
    }

    const pagination = document.getElementById("pagination");
    pagination.innerHTML = html;

    pagination.onclick = (e) => {
        const btn = e.target.closest("button");
        if(!btn) return;

        currentParams.page = parseInt(btn.dataset.page);
        updateURL(currentParams);
        loadBooks(currentParams);
    };
}

function updateURL(currentParams){
    const sanitized = {};
    Object.entries(currentParams).forEach(([key, value]) => {
        if (value !== "" && value !== null && value !== undefined) {
            sanitized[key] = value;
        }
    });
    const query = new URLSearchParams(sanitized).toString();

    window.history.pushState({}, "", 
        BASE_URL + "bookList?" + query
    );
}

async function getCategories(currentParams) {
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
            const categoryList = document.getElementById("categoryList");
            const categories = data.data;
            let html = "";
            categories.forEach(c => {
                // const checked = String(currentParams.categoryID) === String(c.categoryID) ? "checked" : "";

                html += `
                    <li>
                        <input type="checkbox" name="categoryID" data-id="${c.categoryID}" >
                        <p>${c.categoryName}</p>
                    </li>
                `
            });
            categoryList.innerHTML = html;
            console.log("html: ", html);
            console.log("category list: ", categoryList);
            categoryList.onclick = (e) => {
                const el = e.target.closest("[data-id]");
                if(!el) return;
            
                currentParams.categoryID = el.dataset.id;
                currentParams.page = 1;
            
                updateURL(currentParams);
                loadBooks(currentParams);
            };
        } else {
            console.error(data.message);
            alert(data.message);
            return;
        }
    } catch(e) {
        console.error("Error: ", e);
        alert("Error: ", e);
        return;

    }
}

