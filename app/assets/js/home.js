document.addEventListener("DOMContentLoaded", async () => {

    let bestsellerContainer = document.getElementById("best-seller-books");

    if(bestsellerContainer) {

        try {

            const res = await fetch(BASE_URL + "home/getBestSeller");

            const contentType = res.headers.get("Content-Type") || "";

            if(!contentType.includes("application/json")) {

                const text = await res.text();
                console.error("Server did not return JSON:", text.slice(0, 200));
                return;

            }

            const data = await res.json();

            if(data.success) {

                const books = data.data;

                bestsellerContainer.innerHTML = "";

                books.forEach(book => {

                    const item = document.createElement("div");
                    item.classList.add("item");

                    item.innerHTML = `
                        <div class="card col-md-9 col-sm-7 col-lg-12 d-flex m-auto flex-column">
                            <div class="image">
                                <img src="${book.imageURL}" class="">
                            </div>

                            <div class="info">
                                <p class="name">${book.volumeName}</p>

                                <div class="price">
                                    <p class="quotedPrice">${book.quotedPrice}</p>
                                    <p class="salePrice">${book.salePrice}</p>
                                </div>
                            </div>

                            <div class="btn">
                                <button class="btn btn-addCart">Thêm vào giỏ hàng</button>
                                <button class="btn btn-buyNow">Mua ngay</button>
                            </div>

                        </div>
                    `;

                    bestsellerContainer.appendChild(item);

                });

                
                $("#best-seller-books").owlCarousel({
                    loop: true,
                    margin: 10,
                    nav: true,
                    dots: false,
                    responsive: {
                        0: { items: 1 },
                        600: { items: 3 },
                        1000: { items: 4 }
                    }
                });

            }

        } catch(e) {

            console.error("Lỗi tải danh sách sách bán chạy:", e);

        }

    }

});