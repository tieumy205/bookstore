document.addEventListener('DOMContentLoaded', async() => {
    const userInfo = document.getElementById('userInfo');
    const paymentInfo = document.getElementById('paymentInfo');
    const orderItems = document.getElementById('orderItems');
    if(userInfo && paymentInfo && orderItems) {
        await loadOrder(userInfo, paymentInfo, orderItems);
    }
    
    
});

async function loadOrder(userInfo, paymentInfo, orderItems) {
    try {
        const orderID = JSON.parse(localStorage.getItem("orderID")) || "";
        const res = await fetch(BASE_URL + `order/getOrder/${orderID}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const contentType = res.headers.get("Content-Type") || "";
        if(!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON: ", text.slice(0, 200));
            return;
        }
        const data = await res.json();
        if(data.success) {
            console.log(data.data);
            const order = data.data;
            const items = order.items;
            const status = translateStatus(order.status);
            function translateStatus(status) {
                switch(status) {
                    case 'processing':
                        return 'Đang xử lý';
                    case 'confirmed':
                        return 'Đã xác nhận';
                    case 'delivering':
                        return 'Đang giao hàng';
                    case 'completed':
                        return 'Giao hàng thành công';
                    case 'canceled':
                        return 'Đã hủy đơn hàng';
                    default:
                        return status;
                }
            }
            const payment = translatePayment(order.payment);
            function translatePayment(payment) {
                switch(payment) {
                    case 'cash':
                        return 'Thanh toán khi nhận hàng';
                    case 'bank-transfer':
                        return 'Chuyển khoản';
                    case 'online':
                        return 'Thanh toán trực tuyến';
                    default:
                        return payment;
                }
            }
           
            userInfo.innerHTML = `
                <p><strong>Tên:</strong> <span id="name">${order.consigneeName}</span></p>
                <p><strong>SĐT:</strong> <span id="phone">${order.numberPhone}</span></p>
                <p><strong>Địa chỉ:</strong> <span id="address">${order.address}</span></p>
            `;
            paymentInfo.innerHTML = `
                <p><strong>Trạng thái đơn hàng:</strong> <span id="status">${status}</span></p>
                <p><strong>Phương thức thanh toán:</strong> <span id="payment">${payment}</span></p>
                <p><strong>Phí ship:</strong> <span id="ship">${formatCurrency(parsePriceToInt(order.shipCost))}</span></p>
                <h4>Tổng tiền: <span id="total">${formatCurrency(parsePriceToInt(order.totalPrice))}</span></h4>
            `;
            orderItems.innerHTML = `
                ${items.map(item => {
                    const bookName = !item.volumeName ? item.bookName : item.volumeName;
                    return `
                    <div style = "display: flex; align-items: center;">
                        <div class="order-item">
                            <img src="${BASE_URL}${item.imageURL}" alt="${item.volumeName}">
                        </div>
                        <div class="order-item-details">
                            <p class="name">${bookName}</p>
                            <span class="price">${formatCurrency(parsePriceToInt(item.salePrice))}</span> x <span class="quantity">${item.quantity}</span>
                            
                        </div>
                    </div>`;
                }).join('')}
            `;
        }
        else {
            console.error('Lỗi:', data.message);
        }
    } catch(e) {
        console.error('Lỗi:', e);
    }
}

function formatCurrency(amount) {
    return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + "đ";
}

function parsePriceToInt(amount) {
    return parseInt(amount);
}