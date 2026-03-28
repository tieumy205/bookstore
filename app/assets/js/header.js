document.addEventListener("DOMContentLoaded", async () => {
    const categoryContainer = document.getElementById("categoryContainer");
    if(categoryContainer) {
        await loadCategories();
    }
    const userIcon = document.getElementById("user-icon");
    userIcon.addEventListener("click", userDropdown());
    const searchBox = document.querySelector(".search-box");
    searchBox.addEventListener("submit", (e) => {
        e.preventDefault();
        const searchInput = document.getElementById("searchInput").value.trim();
        if(!searchInput) return;
        console.log("search: ", searchInput);
        
        window.location.href = BASE_URL + `bookList?search=${encodeURIComponent(searchInput)}`;
    })
})

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
            const categoryList = document.getElementById("categoryContainer");
            const categories = data.data;
            let html = "";
            categories.forEach(c => {
                const params = {
                    categoryID : c.categoryID,
                    page: 1
                };
                const query = new URLSearchParams(params).toString();
                html += `
                    <div>
                        
                        <li class= "dropdown-item" >
                            <a href="${BASE_URL}bookList?${query}">${c.categoryName}</a>
                        </li>
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

function userDropdown() {
    const userDropdown = document.getElementById("userDropdown");
    const userID = JSON.parse(localStorage.getItem("userID"));
    if(!userID) {
        userDropdown.innerHTML = `
            <div>
                <a href="${BASE_URL}login">Đăng nhập</a>
                <a href="${BASE_URL}register">Đăng ký</a>
            </div>
        `
    } else {
        userDropdown.innerHTML = `
            <div>
                <a href="${BASE_URL}userInfo">Thông tin người dùng</a>
                <a href="${BASE_URL}history/getOrder">Lịch sử mua hàng</a>
                <a href="${BASE_URL}logout/logout">Đăng xuất</a>
            </div>
        `
    }
}

