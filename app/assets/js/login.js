document.addEventListener("DOMContentLoaded", () => {
    let loginForm = document.getElementById("loginForm");

    if (loginForm) {
        const userID = localStorage.getItem("userID");
        if(userID) {
            localStorage.removeItem("userID");
        }
        loginForm.addEventListener("submit", (e) => {
            e.preventDefault();
            auth();
        });
    }
});

async function auth() {

    let username = document.getElementById("username").value;
    let password = document.getElementById("password").value;

    let params = new URLSearchParams({
        username: username,
        password: password
    });

    const res = await fetch(BASE_URL + "login/login", {
        method: "POST",
        headers: {
            "Content-Type" : "application/x-www-form-urlencoded"
        },
        body: params.toString()
    });

    const contentType = res.headers.get("Content-Type") || "";

    if (!contentType.includes("application/json")) {
        const text = await res.text();
        console.error("Server did not return JSON:", text.slice(0, 200));
        alert("Đăng nhập thất bại. Máy chủ trả về lỗi.");
        return;
    }

    const data = await res.json();

    if (data.success) {
        console.log("Đăng nhập thành công");
        alert("Đăng nhập thành công");
        window.location.href = BASE_URL;
        let userID = data.userID;
        localStorage.setItem('userID', JSON.stringify(userID));

    } else {
        console.log("Lỗi đăng nhập: " + data.message);
        alert(data.message);
    }
}