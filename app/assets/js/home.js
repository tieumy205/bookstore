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

    const sliders = document.querySelectorAll(".slider-wrapper");

    sliders.forEach(slider => {

        const track = slider.querySelector(".slider-track");
        const container = slider.querySelector(".slider-container");
        const prevBtn = slider.querySelector(".slider-btn.prev");
        const nextBtn = slider.querySelector(".slider-btn.next");

        if (!track || !container) return;

        let currentPosition = 0;
        let autoSlideInterval;

        const GAP = 20;
        const AUTO_TIME = 4000;

        const getCardWidth = () => {
            const card = slider.querySelector(".product-card");
            return card ? card.offsetWidth + GAP : 0;
        };

        const getStep = () => {
            const w = window.innerWidth;
            if (w < 768) return 1;
            if (w < 1024) return 2;
            return 3;
        };

        const getMaxTranslate = () => {
            return track.scrollWidth - container.offsetWidth;
        };

        const updateSlider = () => {
            track.style.transform = `translateX(${currentPosition}px)`;
        };

        const slideNext = () => {
            const move = getCardWidth() * getStep();
            const maxTranslate = getMaxTranslate();

            currentPosition -= move;

            if (Math.abs(currentPosition) >= maxTranslate) {
                currentPosition = -maxTranslate;
            }

            updateSlider();
        };

        const slidePrev = () => {
            const move = getCardWidth() * getStep();

            currentPosition += move;

            if (currentPosition > 0) currentPosition = 0;

            updateSlider();
        };

        const startAutoSlide = () => {
            stopAutoSlide();

            autoSlideInterval = setInterval(() => {

                if (Math.abs(currentPosition) >= getMaxTranslate()) {
                    currentPosition = 0;
                } else {
                    slideNext();
                }

                updateSlider();

            }, AUTO_TIME);
        };

        const stopAutoSlide = () => {
            clearInterval(autoSlideInterval);
        };

        nextBtn.addEventListener("click", () => {
            slideNext();
            startAutoSlide();
        });

        prevBtn.addEventListener("click", () => {
            slidePrev();
            startAutoSlide();
        });

        container.addEventListener("mouseenter", stopAutoSlide);
        container.addEventListener("mouseleave", startAutoSlide);

        window.addEventListener("resize", () => {
            currentPosition = 0;
            updateSlider();
        });

        track.style.transition = "transform 0.5s ease";

        startAutoSlide();

    });


    /* BOOK QUOTE SECTION */

    const quotes = window.bookQuotes || [];

    const quoteText = document.querySelector(".quote-text");
    const quoteBookImg = document.querySelector("#quoteBookImg");
    const quoteBookLink = document.querySelector("#quoteBookLink");
    const dots = document.querySelectorAll(".dot");

    if (quotes.length && quoteText && quoteBookImg && quoteBookLink) {

        const changeQuote = (index) => {

            quoteText.textContent = quotes[index].quote;
            quoteBookImg.src = "picture/books/" + quotes[index].img;
            quoteBookLink.href = quotes[index].link;

            dots.forEach(d => d.classList.remove("active"));
            if (dots[index]) dots[index].classList.add("active");
        };

        dots.forEach(dot => {

            dot.addEventListener("click", () => {
                const index = dot.dataset.index;
                changeQuote(index);
            });

        });

        let currentQuote = 0;

        setInterval(() => {

            currentQuote++;

            if (currentQuote >= quotes.length) {
                currentQuote = 0;
            }

            changeQuote(currentQuote);

        }, 5000);

    }

});