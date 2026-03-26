


document.addEventListener("DOMContentLoaded", async () => {
    const checkoutContainer = document.getElementById("checkout-container");
    if(checkoutContainer) {
        const editionID = getEditionIDFromPath();
        if(editionID) {

            console.log("editionID : ", editionID);
            await loadCheckoutPage(editionID);
            
        } else {
            await loadEditionFromCart();
        }
        await loadUserInfo();
        getPaymentMethod();
        const address = JSON.parse(localStorage.getItem("address")) || [];
        const addressDefault = JSON.parse(localStorage.getItem("addressDefault")) || "";
        renderInfo(addressDefault);
        renderFirstModal();
        await loadProvinces1();
    }

    // Mở modal địa chỉ bằng JS: tránh trạng thái Bootstrap bị kẹt sau modal lồng nhau / cleanup backdrop
    const editBtn = document.getElementById("edit-address");
    if (editBtn) {
        editBtn.addEventListener("click", (e) => {
            e.preventDefault();
            openModalEditAddress();
        });
    }
})

/**
 * Mở lại #modalEditAddress ổn định sau khi thêm/sửa địa chỉ (modal lồng nhau dễ làm instance/backdrop lệch).
 */
function openModalEditAddress() {
    const modalEl = document.getElementById("modalEditAddress");
    if (!modalEl) return;

    // Dọn backdrop / class body sót lại (không dispose khi modal đang thật sự mở)
    document.querySelectorAll(".modal-backdrop").forEach((b) => b.remove());
    document.body.classList.remove("modal-open");
    document.body.style.removeProperty("padding-right");
    document.body.style.removeProperty("overflow");

    modalEl.classList.remove("show");
    modalEl.style.display = "none";
    modalEl.setAttribute("aria-hidden", "true");
    modalEl.removeAttribute("aria-modal");

    const old = bootstrap.Modal.getInstance(modalEl);
    if (old) old.dispose();

    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

async function loadEditionFromCart() {
    try {
        const selectedItems = JSON.parse(localStorage.getItem("selectedItems")) || [];

        console.log("selected items: ", selectedItems);

        if (selectedItems.length === 0) {
            alert("Không có sản phẩm được chọn");
            return;
        }

        const items = new URLSearchParams({
            selectedItems: JSON.stringify(selectedItems)
        });

        const res = await fetch(BASE_URL + `checkout/getEditions`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: items.toString()
        });

        const contentType = res.headers.get("Content-Type") || "";
        if (!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON: ", text);
            return;
        }

        const data = await res.json();

        if (data.success) {
            const books = data.data;

            console.log("books:", books);

            localStorage.setItem("books", JSON.stringify(books));

            const productContainer = document.getElementById("productContainer");

            let html = "";

            books.forEach(book => {
                const bookName = book.volumeName || book.bookName;

                html += `
                <div class="product">
                    <img src="${BASE_URL}${book.imageURL}" alt="">

                    <div class="product-info">
                        <p class="name">${bookName}</p>
                        <p class="price">
                            ${formatCurrency(parsePriceToInt(book.salePrice))} x ${book.quantity}
                        </p>
                    </div>
                </div>
                `;
            });

            productContainer.innerHTML = html;
        } else {
            console.error("Error:", data.message);
            alert(data.message);
            return;
        }

    } catch (e) {
        console.error("Lỗi tải dữ liệu:", e);
        alert("Lỗi: ", data.message);
    }
}


// nhấn nút mua hàng từ trang home/ bookDetail/ bookList -> quantity = 1
async function loadCheckoutPage(editionID) {
    try {
        console.log("base url: ", BASE_URL);
        const res = await fetch(BASE_URL + `checkout/getEditionCheckout/${editionID}`, {
            headers: {"X-Requested-With" : "XMLHttpRequest"}
        });
        const contentType = res.headers.get("Content-Type") || "";
        console.log("content type: ", contentType);
        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON: ", text);
            return;
        }
        const data = await res.json();
        if(data.success) {
            console.log("book: ", data.data);
            const book = data.data;
            
            const bookUni = book[0];
            const productContainer = document.getElementById("productContainer");
            const quantity = 1;
            book[0].quantity = quantity;
            localStorage.setItem("books", JSON.stringify(book));
            
            let html = "";
            const bookName = (bookUni.volumeName) ? bookUni.volumeName : bookUni.bookName;
            html += `
            <div class="product">
                <img src="${BASE_URL}${bookUni.imageURL}" alt="">

                <div class="product-info">
                    <p class="name">${bookName}</p>

                    <p class="price">${formatCurrency(parsePriceToInt(bookUni.salePrice))} x ${quantity}</p>
                </div>
            </div>
            `
            productContainer.innerHTML = html;
        } else {
            console.error("Error:", data.message);
            alert(data.message);
            return;
        }
    } catch(e) {
        console.error("Lỗi tải dữ liệu các sản phẩm cần mua: ", e);
        return;
    }
}



async function loadUserInfo() {
    try{
        const res = await fetch(BASE_URL + "checkout/getUserInfo", {
            headers: {"X-Requested-With": "XMLHttpRequest"}
        });

        const contentType = res.headers.get("Content-Type") || "";

        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON: ", text.slice(0, 200));
            return;
        }

        const data = await res.json();

        if(data.success) {
            const user = data.data;
            
            const addressDefautl = user.address[0];
            const address = user.address;
            
            const addressLength = user.address.length;
            
            //lưu người dùng vào localstorage
            localStorage.setItem("address", JSON.stringify(address));

            localStorage.setItem("addressDefault", JSON.stringify(addressDefautl));
            
            
            
            

            //hiển thị thông tin ở modal
            // renderFirstModal(address);

            //modal 3 thêm địa chỉ
            // await loadProvinces1();
            

        } else {
            console.error("Error:", data.message);
            alert(data.message);
            return;
        }

    } catch(e) {
        console.error("Không tải được dữ liệu người dùng: ", e);
    }
}

function renderInfo(address) {
    document.getElementById("consigneeNameCheckout").textContent = address.consigneeName || "";
    document.getElementById("numberPhoneCheckout").textContent = address.numberPhone || "";
    document.getElementById("addressCheckout").textContent =
    address.detailAddress + ", " + address.district + ", " + address.province || "";
}



function getEditionIDFromPath() {
    const parts = window.location.pathname.replace(/^\/|\/$/g, "").split("/");
    console.log("href: ", window.location.pathname);
    console.log("parts: ", parts);
    const idx = parts.findIndex(p => p.toLowerCase() === "checkout");
    if(idx < 0 ) return null;
    const action = parts[idx + 1] || "";
    if(action.toLowerCase() !== "geteditioncheckout") return;
    return parts[idx + 2] || null;
}

function formatCurrency(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "đ";
}

function parsePriceToInt(amount) {
    return parseInt(amount);
}

/**
 * Chỉ xóa backdrop thừa khi số backdrop > số modal đang .show (tránh phá trạng thái Bootstrap).
 */
function cleanupModalBackdrop() {
    const openModals = document.querySelectorAll(".modal.show");
    const backdrops = document.querySelectorAll(".modal-backdrop");
    if (openModals.length === 0 && backdrops.length > 0) {
        backdrops.forEach((el) => el.remove());
        document.body.classList.remove("modal-open");
        document.body.style.removeProperty("padding-right");
        document.body.style.removeProperty("overflow");
        return;
    }
    if (backdrops.length > openModals.length) {
        for (let i = openModals.length; i < backdrops.length; i++) {
            backdrops[i]?.remove();
        }
    }
}

function saveAddress(address, index) {
    
    const addressChecked = address[index];
    localStorage.setItem("addressSelected", JSON.stringify(addressChecked));
    

    renderInfo(addressChecked);

    

    const modalEl = document.getElementById("modalEditAddress");
    document.activeElement.blur();
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.hide();
            
        
}

async function addAddress() {
    try {
        const consigneeName = document.getElementById("consigneeNameCheck").value.trim();
        const numberPhone = document.getElementById("numberPhoneCheck").value.trim();
        const province = document.getElementById("provinceCheck").selectedOptions[0].text;
        const district = document.getElementById("districtCheck").selectedOptions[0].text;
        const detailAddress = document.getElementById("detailAddressCheck").value.trim();
        console.log("consigneeName: ", consigneeName);
        console.log("numberPhone: ", numberPhone);
        console.log("province: ", province);
        console.log("district: ", district);
        console.log("detailAddress: ", detailAddress);

        if(!consigneeName || !numberPhone || !province || !district || !detailAddress) {
            console.error("Vui lòng nhập đầy đủ thông tin");
            alert("Vui lòng nhập đầy đủ thông tin");
            return;
        }

        const newAddress = new URLSearchParams({
            consigneeName,
            numberPhone,
            province,
            district,
            detailAddress
        });
        const res = await fetch(BASE_URL + `checkout/addAddress`, {
            method : "POST",
            headers : {
                "Content-Type" : "application/x-www-form-urlencoded"
            },
            body : newAddress.toString()
        });

        const contentType = res.headers.get("Content-Type") || "";
        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON: ", text.slice(0, 200));
            return;
        }

        const data = await res.json();
        if(data.success) {
            alert("Thêm địa chỉ thành công");

            // 1. load lại danh sách địa chỉ từ server
            await loadUserInfo();
            renderFirstModal();
            

            // 2. Chỉ đóng modal "Thêm địa chỉ". Modal cha (#modalEditAddress) vẫn đang mở bên dưới —
            // KHÔNG gọi show() lại trên modal cha (dễ tạo thêm backdrop / modal-backdrop kẹt).
            const addModalEl = document.getElementById("addInfo");
            const addModal = bootstrap.Modal.getOrCreateInstance(addModalEl);
            addModalEl.addEventListener(
                "hidden.bs.modal",
                () => {
                    cleanupModalBackdrop();
                },
                { once: true }
            );
            addModal.hide();

            const editAddress = document.getElementById("modalEditAddress");
            document.activeElement.blur();
            const editAddressModal = bootstrap.Modal.getOrCreateInstance(editAddress);
            editAddressModal.show();
            
            return;
        } else {
            console.error("Error: ", data.message);
            return;
        }
    } catch(e) {
        console.error("Error: ", e);
        alert("Error: ", e);
        return;
    }
}

async function updateAddress() {
    try {
        const consigneeName = document.getElementById("updateConsigneeName").value.trim();
        const numberPhone = document.getElementById("updateNumberPhone").value.trim();
        const province = document.getElementById("updateProvince").selectedOptions[0].text;
        const district = document.getElementById("updateDistrict").selectedOptions[0].text;
        const detailAddress = document.getElementById("updateDetailAddress").value.trim();
        const addressUpdateID = JSON.parse(localStorage.getItem("addressIDUpdate"));

        console.log("consigneeName: ", consigneeName);
        console.log("numberPhone: ", numberPhone);
        console.log("province: ", province);
        console.log("district: ", district);
        console.log("detailAddress: ", detailAddress);
        console.log("addressUpdateID: ", addressUpdateID);

        const updateAddress = new URLSearchParams({
            consigneeName,
            numberPhone,
            province,
            district,
            detailAddress,
            addressID : addressUpdateID
        });

        const res = await fetch(BASE_URL + `checkout/updateAddress`, {
            method : "POST",
            headers : {
                "Content-Type" : "application/x-www-form-urlencoded"
            },
            body : updateAddress.toString()
        });
        const contentType = res.headers.get("Content-Type") || "";
        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON: ", text.slice(0, 200));
            return;
        }

        const data = await res.json();
        if(data.success) {

            await loadUserInfo();
            renderFirstModal();
            

            // 2. Chỉ đóng modal cập nhật; modal chọn địa chỉ vẫn mở — không show() lại.
            const updateModalEl = document.getElementById("updateInfo");
            document.activeElement.blur();
            const updateModal = bootstrap.Modal.getOrCreateInstance(updateModalEl);
            updateModalEl.addEventListener(
                "hidden.bs.modal",
                () => {
                    cleanupModalBackdrop();
                },
                { once: true }
            );
            updateModal.hide();

            const editAddress = document.getElementById("modalEditAddress");
            document.activeElement.blur();
            const editAddressModal = bootstrap.Modal.getOrCreateInstance(editAddress);
            editAddressModal.show();

            return;

        } else {
            console.error("Error: ", data.message);
            return;
        }

    } catch(e) {
        console.error("Error: ", e);
        alert("Error: ", e);
        return;
    }
}



async function loadProvinces() {
    try {
        console.log("Đang tải danh sách tỉnh/thành phố...");
        let provinceSelect = document.getElementById("updateProvince");

        const res = await fetch("https://provinces.open-api.vn/api/p/");
        const provinces = await res.json();
        // provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
        provinceSelect.innerHTML += provinces.map( p =>
            `<option value="${p.code}"> ${p.name}</option>`

        ).join("");

        document.getElementById("updateProvince").addEventListener("change", function(){

            let provinceCode = this.value;

            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
            .then(res => res.json())
            .then(data => {

                let district = document.getElementById("updateDistrict");
                district.innerHTML = '<option value="">Chọn huyện</option>';

                data.districts.forEach(d => {
                    district.innerHTML += `<option value="${d.code}">${d.name}</option>`;
                });

            });

        });
        

        
    } catch(e) {
        console.error("Lỗi tải danh sách tỉnh/thành phố:", e);
    }
}

async function loadProvinces1() {
    try {
        console.log("Đang tải danh sách tỉnh/thành phố...");
        let provinceSelect = document.getElementById("provinceCheck");

        const res = await fetch("https://provinces.open-api.vn/api/p/");
        const provinces = await res.json();
        // provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
        provinceSelect.innerHTML += provinces.map( p =>
            `<option value="${p.code}"> ${p.name}</option>`

        ).join("");

        document.getElementById("provinceCheck").addEventListener("change", function(){

            let provinceCode = this.value;

            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
            .then(res => res.json())
            .then(data => {

                let district = document.getElementById("districtCheck");
                district.innerHTML = '<option value="">Chọn huyện</option>';

                data.districts.forEach(d => {
                    district.innerHTML += `<option value="${d.code}">${d.name}</option>`;
                });

            });

        });
        

        
    } catch(e) {
        console.error("Lỗi tải danh sách tỉnh/thành phố:", e);
    }
}


function renderFirstModal() {
    const address = JSON.parse(localStorage.getItem("address")) || [];
    const formAddress = document.getElementById("formAddress"); 
    
    let html = ``;
    address.forEach((a, index) => {
        html += `
            <div class="form-check" style="display: flex; justify-content: space-between;">
                <div>
                    <input class="form-check-input" type="radio" name="radioDefault" data-index="${index}">
                    
                    <label class="form-check-label" for="">
                        <span id= "consigneeName${a.id}">${a.consigneeName} | </span>
                        <span id= "numberPhone${a.id}">${a.numberPhone}</span><br>
                        <span id= "address${a.id}">${a.detailAddress}, ${a.district}, ${a.province}</span>
                    </label>
                </div>
                <button class = "btn btn-link btn-primary btnUpdate" data-index="${index}" data-bs-target="#updateInfo" data-bs-toggle="modal">Cập nhật</button>
            </div>
            
        `
    })
    formAddress.innerHTML = html;


    formAddress.addEventListener("click", async (e) => {
        const index = e.target.dataset.index;

        if (e.target.classList.contains("btnUpdate")) {
            await renderUpdateModal(address, index);
        }
        if(e.target.classList.contains("form-check-input")) {
            await saveAddress(address, index);
        }
    });
}

async function renderUpdateModal(address, index) {

        const addressID = address[index].id;
        
        localStorage.setItem("addressIDUpdate", JSON.stringify(addressID));
        const updateAddress = document.getElementById("updateAddress");
        updateAddress.innerHTML = `
            <div class="formInfo" style = "display: flex; justify-content: space-between">
                <input value="${address[index].consigneeName}" type="text" class="form-control" id="updateConsigneeName" required>
                <input value="${address[index].numberPhone}" type="tel" class="form-control" id="updateNumberPhone" required>
            </div>
            <div class="formSelect" style = "display: flex; justify-content: space-between">
                <select name="" id="updateProvince">
                    <option value="">${address[index].province}</option>
                </select>

                <select name="" id="updateDistrict">
                    <option value="">${address[index].district}</option>
                </select>
            </div>

            <input value="${address[index].detailAddress}" type="text" class="form-control" id="updateDetailAddress" required>
        
        `
        await loadProvinces();
}

function getPaymentMethod() {
    const paymentMethod = document.getElementById("paymentMethod").value;
    console.log("method: ", paymentMethod);
    localStorage.setItem("method", JSON.stringify(paymentMethod));
}

async function checkout() {
    try {
        const books = JSON.parse(localStorage.getItem("books")) || [];
        const method = JSON.parse(localStorage.getItem("method")) || "";
        const addressSelected = JSON.parse(localStorage.getItem("addressSelected")) || "";
        const addressID = addressSelected.id;
        let paymentID = null;
        switch (method) {
            case "cash" :
                paymentID = 1;
                break;
            case "bank-transfer":
                paymentID = 2;
                break;
            case "online":
                paymentID = 3;
                break;
            default:
                paymentID = null;
                break;

        };
        
        const newOrder = new URLSearchParams({
            addressID,
            paymentID,
            books : JSON.stringify(books)
        });
        const res = await fetch(BASE_URL + `order/addOrder`, {
            method : "POST",
            headers : {
                "Content-Type" : "application/x-www-form-urlencoded"
            },
            body : newOrder.toString()
        });
        const contentType = res.headers.get("Content-Type") || "";
        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON: ", text.slice(0, 200));
            return;
        }

        const data = await res.json();
        if(data.success) {
            alert("Mua hàng thành công");
            const orderID = data.orderID;
            localStorage.setItem("orderID", JSON.stringify(orderID));
            window.location.href = BASE_URL + `order`;
            // localStorage.clear();
            return;
        } else {
            console.error("Error: ", data.message);
            alert("Error: ", data.message);
            return;
        }
    } catch(e) {
        console.error("Error", e);
        alert("Error: ", e);
        return;
    }
}

