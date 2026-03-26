document.addEventListener("DOMContentLoaded", async () => {
    const cartItemsList = document.getElementById("cartItemsList");
    if(cartItemsList) {
        await loadCart();
    }
})

function formatCurrency(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "đ";
}

function parsePriceToInt(amount) {
    return parseInt(amount);
}

function calTotalPrice() {
    const cartItems = document.querySelectorAll(".cart-item");
    let subtotal = 0;
    const selectedItems = [];
    cartItems.forEach(item => {
        const checkbox = item.querySelector(".item-checkbox");
        const priceEl = item.querySelector(".item-price");
        const qtyInput = item.querySelector(".quantity-input");

        console.log("checkbox:", checkbox);
        console.log("dataset:", checkbox?.dataset);
        console.log("id:", checkbox?.dataset?.id);

        if (!checkbox || !priceEl || !qtyInput) return;
        if (!checkbox.checked) return;

        const editionID = checkbox.dataset.id;
        console.log("edition id111: ", editionID);
        
        const rawPrice = priceEl.textContent.replace(/[^\d]/g, "");
        const price = parseInt(rawPrice, 10) || 0;
        const qty = parseInt(qtyInput.value, 10) || 0;

        selectedItems.push({
            editionID,
            quantity : qty
        });

        subtotal += price * qty;
    });
    localStorage.setItem("selectedItems", JSON.stringify(selectedItems));
    const subtotalEl = document.getElementById("subtotal");
    const totalEl = document.getElementById("total");

    if (subtotalEl) subtotalEl.textContent = formatCurrency(subtotal);
    if (totalEl) totalEl.textContent = formatCurrency(subtotal);
}

async function loadCart(items) {
    try {
        const res = await fetch(BASE_URL + "cart/getItemCart");
        const contentType = res.headers.get("Content-Type") || "";
        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON " + text.slice(0, 200));
            return;
        }
        const data = await res.json();
        console.log(data.data);
        if(data.success) {
            const cartHeader = document.getElementById("cartHeader");
            const cartItemsListContainer = document.getElementById("cartItemsList");
            const items = data.data;
            if(items.length === 0) {
                cartHeader.remove();
                cartItemsListContainer.innerHTML = '<div class="empty-cart">Giỏ hàng của bạn đang trống</div>';
                return;
            }
           
            console.log("data: " + data.data);
            let html = '';
            items.forEach(item => {
                console.log("stock:", item.stockQuantity);
                html += `
                    <div class="cart-item">
                        <input type="checkbox" class="item-checkbox" data-id="${item.editionID}" onchange="calTotalPrice()">
                        <div class="item-image">
                            <img src="${BASE_URL}${item.imageURL}" alt="${item.volumeName}" onerror="this.src='${BASE_URL}app/assets/images/default-product.jpg'">
                        </div>
                        <div class="item-details">
                            <div class="item-name">${item.volumeName}</div>
                            <div class="item-price">${formatCurrency(parsePriceToInt(item.salePrice))}</div>
                            <div class="item-quantity">
                                <button class="quantity-btn minus" onclick="updateQuantity(${item.editionID}, ${item.quantity}, -1)">-</button>
                                <input type="number" class="quantity-input" value="${item.quantity}" min="1" max="${item.stockQuantity}" data-id="${item.editionID}" onchange="updateQuantity(${item.editionID}, this.value, 0)">
                                <button class="quantity-btn plus" onclick="updateQuantity(${item.editionID}, ${item.quantity}, 1)">+</button>
                                <span class="item-remove" onclick="removeItem(${item.editionID})">
                                    <i class="bi bi-trash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                `
            });
            cartItemsListContainer.innerHTML = html;
            calTotalPrice();
        } else {
            console.error(data.message);
        }
    } catch(e) {
        console.error("Lỗi tải giỏ hàng:", e);
        alert("Không thể tải giỏ hàng. Vui lòng thử lại");
    }
}

function toggleSelectAll(source) {
    const checkboxes = document.querySelectorAll(".cart-item .item-checkbox");
    checkboxes.forEach(cb => {
        cb.checked = source.checked;
    });
    calTotalPrice();
}

async function removeItem(editionID) {
    const item = document.querySelector(`.cart-item .item-checkbox[data-id="${editionID}"]`)?.closest(".cart-item");
    if (!item) return;

    try {
        const res = await fetch(BASE_URL + `cart/remove/${editionID}`, {
            method: "POST"
        });
        const contentType = res.headers.get("Content-Type") || "";
        if (!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Remove API did not return JSON:", text.slice(0, 200));
            return;
        }
        const data = await res.json();
        if (data.success) {
            item.remove();
            calTotalPrice();
        } else {
            alert(data.message || "Xóa sản phẩm thất bại");
        }
    } catch (e) {
        console.error("Lỗi xoá sản phẩm:", e);
        alert("Không thể xoá sản phẩm. Vui lòng thử lại");
    }
}

async function updateQuantity(editionID, currentQty, delta) {
    const input = document.querySelector(`.cart-item .quantity-input[data-id="${editionID}"]`);
    if (!input) return;

    let qty = parseInt(input.value, 10);
   
    if (isNaN(qty)) {
        qty = parseInt(currentQty, 10) || 1;
    }
    qty += delta;
    if (qty < 1) qty = 1;
    const max = parseInt(input.getAttribute("max"), 10);
    console.log("qty:", qty);
    console.log("max:", max);
    if (!isNaN(max) && max > 0 && qty > max) {
        alert("Số lượng vượt quá tồn kho");
        qty = max;
        input.value = qty;
        return;
    }

    input.value = qty;
    calTotalPrice();

    try {
        const body = new URLSearchParams({ quantity: qty.toString() });
        const res = await fetch(BASE_URL + `cart/updateQuantity/${editionID}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: body.toString()
        });
        const contentType = res.headers.get("Content-Type") || "";
        if (!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("UpdateQuantity API did not return JSON:", text.slice(0, 200));
            return;
        }
        const data = await res.json();
        if (!data.success) {
            alert(data.message || "Cập nhật số lượng thất bại");
        }
    } catch (e) {
        console.error("Lỗi cập nhật số lượng:", e);
        alert("Không thể cập nhật số lượng. Vui lòng thử lại");
    }
}

async function getCheckout() {
    const selectedItems = JSON.parse(localStorage.getItem("selectedItems"));
    if(selectedItems.length === 0) {
        alert("Không có sản phẩm nào được chọn");
        return;
    }
    window.location.href = BASE_URL + `checkout`;
}