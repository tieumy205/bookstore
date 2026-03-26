document.addEventListener("DOMContentLoaded", async () => {
    const editionID = getEditionIdFromPath();
    console.log("editionID: ", editionID);
    if (!editionID) return;

    try {
        const res = await fetch(`${BASE_URL}bookDetail/getDetail/${editionID}`, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        });
        const contentType = res.headers.get("Content-Type") || "";
        console.log("content type: ", contentType);
        if (!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON:", text.slice(0, 200));
            return;
        }
        const data = await res.json();
        if (!data.success || !data.data || !data.data.length) {
            console.error(data.message || "Không có dữ liệu chi tiết sách");
            return;
        }

        const book = data.data[0];
        console.log("book: ", book);
        renderBookDetail(book);
        await loadRelativeBook(book.editionID);
    } catch (e) {
        console.error("Không tải được thông tin chi tiết sách:", e);
    }
})

function getEditionIdFromPath() {
    console.log("href: ", window.location.pathname);
    const parts = window.location.pathname.replace(/^\/|\/$/g, "").split("/");
     console.log("parts: ", parts);
    const idx = parts.findIndex(p => p.toLowerCase() === "bookdetail");
    if (idx < 0) return null;
    const action = parts[idx + 1] || "";
    if (action.toLowerCase() !== "getdetail") return null;
    return parts[idx + 2] || null;
}

function renderBookDetail(book) {
    const img = document.getElementById("productImage");
    const name = document.getElementById("productName");
    const price = document.getElementById("productPrice");
    const desc = document.getElementById("productDescription");
    const addToCart = document.getElementById("add-to-cart");
    const buyNow = document.getElementById("buy-now");
    const description = document.getElementById("description");
    const infoDetail = document.getElementById("info");
    const productCardDetail = document.querySelector(".product-card-detail");
    

    if (img) img.src = BASE_URL + (book.imageURL || "");
    if (name) name.textContent = book.volumeName || book.bookName || "";

    if (price) {
        const quoted = book.quotedPrice != null ? Number(book.quotedPrice) : null;
        const sale = book.salePrice != null ? Number(book.salePrice) : null;
        price.innerHTML = `
            <div class="price">
                ${quoted != null ? `<span class="quotedPrice">${formatCurrency(quoted)}</span>` : ""}
                ${sale != null ? `<span class="salePrice">${formatCurrency(sale)}</span>` : ""}
            </div>
        `;
    }

    if (desc) desc.textContent = book.description || "";

    if (addToCart) addToCart.setAttribute("data-id", book.editionID);
    if (buyNow) buyNow.setAttribute("data-id", book.editionID);

    let coverType = (book.coverType === "paperBack") 
        ? "Bìa mềm"
        : "Bìa cứng";

    let bookName = (book.volumeName === "")
        ? book.bookName
        : book.volumeName ;
    
    if(description) description.innerText = book.description || "";
    if(infoDetail) infoDetail.innerHTML = `
        <p id="bookName">${bookName}</p>
        <p id="author">${book.authorName}</p>
        <p id="coverType">${coverType}</p>
        <p id="publicationYear">${book.publicationYear}</p>
    `;

    addItemClicks(productCardDetail);
    buyItemClicks(productCardDetail);
}

function formatCurrency(amount) {
    const n = Number(amount) || 0;
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "đ";
}

async function loadRelativeBook(editionID) {
    try{
        const res = await fetch(BASE_URL + `bookDetail/getRelativeBook/${editionID}`);
        const contentType = res.headers.get("Content-Type") || "";
        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON: ", text.slice(0, 200));
            return;
        }
        const data = await res.json();
        if(data.success) {
            console.log("data: ", data.data);
            const books = data.data;
            const relatedBooks = document.getElementById("related-products");
            writeBookDetailClicks(relatedBooks);
            
            
            let html = "";
            books.forEach(book => {

                html += `
                    <div class="item">
                        <div class="card col-md-9 col-sm-7 col-lg-12 d-flex m-auto flex-column">

                            <div class="image" data-id="${book.editionID}">
                                <img src="${BASE_URL}${book.imageURL}">
                            </div>

                            <div class="info">
                                <p class="name" data-id="${book.editionID}">${book.volumeName}</p>

                                <div class="price">
                                    <p class="quotedPrice">${book.quotedPrice}</p>
                                    <p class="salePrice">${book.salePrice}</p>
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

            
            relatedBooks.innerHTML = html;
            addItemClicks(relatedBooks);
            buyItemClicks(relatedBooks);

            $("#related-products").owlCarousel({
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
        }
    } catch(e) {
        console.error("Lỗi tải danh sách các sách liên quan: ", e);
        alert("Lỗi tải danh sách các sách liên quan. Vui lòng thử lại");
        return;
    }
}

function writeBookDetailClicks(container) {
    container.addEventListener("click", (e) => {
        const el = e.target.closest("[data-id]");
        if (!el) return;
        if (e.target.closest("button")) return;
        const editionID = el.getAttribute("data-id");
        if (!editionID) return;
        window.location.href = BASE_URL + `bookDetail/getDetail/${editionID}`;
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