document.addEventListener("DOMContentLoaded", () => {
    loadOrderHistory();
});

async function loadOrderHistory() {

    const container = document.getElementById("orderList");

    try {

        const res = await fetch(BASE_URL + "history/getOrder", {
            headers: {
                "X-Requested-With": "XMLHttpRequest"
            }
        });

        const data = await res.json();

        if (!data.success) {
            container.innerHTML = "<p>Không tải được đơn hàng</p>";
            return;
        }

        const orders = data.data.orders;

        if (!orders || orders.length === 0) {
            container.innerHTML = "<p>Bạn chưa có đơn hàng nào</p>";
            return;
        }

        let html = "";

        orders.forEach(order => {

            const firstItem = order.items[0];
            const moreItems = order.items.slice(1);
            const hasMore = moreItems.length > 0;

            const status = translateStatus(order.status);

            const bookName = firstItem.volumeName || firstItem.bookName;

            html += `
            <div class="order-card-history">

                <div class="order-header-history">
                    <div class="order-id-history">
                        Đơn hàng #${order.orderID}
                    </div>

                    <div class="order-status-history status-${order.status}">
                        ${status}
                    </div>
                </div>

                <div class="product-info-history">

                    <div class="product-image-history">
                        <img src="${BASE_URL}${firstItem.imageURL}">
                    </div>

                    <div class="product-details-history">

                        <div class="text-start">

                            <div class="product-name-history">
                                ${bookName}
                            </div>

                            <div class="product-author">
                                ${firstItem.authorName}
                            </div>

                        </div>

                        <div class="text-end">

                            <div class="product-price-history">
                                ${Number(firstItem.salePrice).toLocaleString("vi-VN")}đ 
                                x ${firstItem.quantity}
                            </div>

                        </div>

                    </div>

                </div>

                ${
                    hasMore ? `
                    <div class="text-end-add">
                        <button 
                            class="view-more-btn-history"
                            onclick="toggleItems(${order.orderID})"
                        >
                            Xem thêm ${moreItems.length} sản phẩm
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>

                    <div 
                        class="additional-items-history"
                        id="order-items-${order.orderID}"
                        style="display:none"
                    >

                        ${moreItems.map(item => `
                        
                        <div class="additional-item-history">

                            <div class="product-image-history">
                                <img src="${BASE_URL}${item.imageURL}">
                            </div>

                            <div class="product-details-history">

                                <div class="text-start">

                                    <div class="product-name-history">
                                        ${item.volumeName || item.bookName}
                                    </div>

                                    <div class="product-author">
                                        ${item.authorName}
                                    </div>

                                </div>

                                <div class="text-end">

                                    <div class="product-price-history">
                                        ${Number(item.salePrice).toLocaleString("vi-VN")}đ 
                                        x ${item.quantity}
                                    </div>

                                </div>

                            </div>

                        </div>

                        `).join("")}

                    </div>
                    ` : ""
                }

                <div class="order-footer-history">

                    <div class="order-total-history">
                        Thành tiền:
                        <span>
                            ${Number(order.totalPrice).toLocaleString("vi-VN")}đ
                        </span>
                    </div>

                </div>

            </div>
            `;
        });

        container.innerHTML = html;

    } catch (err) {

        console.error("Lỗi tải lịch sử:", err);

        container.innerHTML = `
            <p style="color:red">
                Lỗi tải lịch sử đơn hàng
            </p>
        `;
    }
}

function translateStatus(status) {

    switch (status) {

        case "processing":
            return "Đang xử lý";

        case "confirmed":
            return "Đã xác nhận";

        case "delivering":
            return "Đang giao hàng";

        case "completed":
            return "Giao hàng thành công";

        case "canceled":
            return "Đã hủy đơn hàng";

        default:
            return status;
    }
}

function toggleItems(orderID) {

    const el = document.getElementById(`order-items-${orderID}`);

    if (!el) return;

    if (el.style.display === "none") {
        el.style.display = "block";
    } else {
        el.style.display = "none";
    }
}