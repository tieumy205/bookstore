document.addEventListener("DOMContentLoaded", () => {
    // danh sách tỉnh/thành phố cần được tải ngay khi trang mở
    loadProvinces();

    let signupForm = document.getElementById("signupForm");
    if (signupForm) {
        signupForm.addEventListener("submit", (e) => {
            e.preventDefault();
            signup();
        });
    }
});

async function loadProvinces() {
    try {
        console.log("Đang tải danh sách tỉnh/thành phố...");
        let provinceSelect = document.getElementById("province");

        const res = await fetch("https://provinces.open-api.vn/api/p/");
        const provinces = await res.json();
        provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
        provinceSelect.innerHTML += provinces.map( p =>
            `<option value="${p.code}"> ${p.name}</option>`

        ).join("");

        document.getElementById("province").addEventListener("change", function(){

            let provinceCode = this.value;

            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
            .then(res => res.json())
            .then(data => {

                let district = document.getElementById("district");
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

async function signup() {

    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();
    let confirmPassword = document.getElementById("confirmPassword").value.trim();
    let numberPhone = document.getElementById("numberPhone").value.trim();
    let detailAddress = document.getElementById("detailAddress").value.trim();

    let provinceCode = document.getElementById("province").value;
    let districtCode = document.getElementById("district").value;

    let provinceName = document.getElementById("province").selectedOptions[0].text;
    let districtName = document.getElementById("district").selectedOptions[0].text;

    if(password !== confirmPassword){
        alert("Mật khẩu không khớp");
        return;
    }

    if(!username || !password || !numberPhone || !provinceCode || !districtCode || !detailAddress){
        alert("Vui lòng điền đầy đủ thông tin");
        return;
    }

    try{

        const params = new URLSearchParams({
            username,
            password,
            numberPhone,
            province: provinceName,
            district: districtName,
            detailAddress
        });

        const res = await fetch(BASE_URL + "register/register", {
            method:"POST",
            headers:{
                "Content-Type":"application/x-www-form-urlencoded"
            },
            body: params.toString()
        });

        const contentType = res.headers.get("Content-Type") || "";
        if (!contentType.includes("application/json")) {
            const text = await res.text();
            console.error("Server did not return JSON:", text.slice(0, 200));
            alert("Đăng ký thất bại. Máy chủ trả về lỗi.");
            return;
        }

        const data = await res.json();
        alert(data.message || (data.success ? "Đăng ký thành công" : "Đăng ký thất bại"));
        if(data.success) {
            window.location.href = BASE_URL + "login";
        }

    }catch(e){
        console.error(e);
        alert("Đăng ký thất bại");
    }

}


