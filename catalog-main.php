<div class="catalog-main">
    <div class="main1">
        <div class="main-container1">
            <div class="title1">
                Каталог
            </div>
        </div>
        <div class="filter-cards">
            <div class="filter">
                <div class="price">Цена</div>
                <div class="slider" id="Slider"></div>
                <div class="price-values">
                    <span id="sliderMinValue"></span>
                    <span id="sliderMaxValue"></span>
                </div>
                <div class="brend">Бренд</div>
                <div class="brend-card" id="brand-filters"></div>
                <div class="size">Размер</div>
                <div class="size-card" id="size-filters"></div>
                <div class="color">Цвет</div>
                <div class="color-card" id="color-filters"></div>
            </div>

            <div class="card-container">
                <div class="group-product" id="catalog-products-container">
                    <div class="card-product">Загрузка...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('Slider');
        
        if (slider && window.noUiSlider) {
            noUiSlider.create(slider, {
                start: [2000, 50000],
                connect: true,
                step: 500,
                range: {
                    'min': 2000,
                    'max': 50000
                },
                format: {
                    to: value => Math.round(value),
                    from: value => Math.round(value)
                }
            });
            
            const minValueSpan = document.getElementById('sliderMinValue');
            const maxValueSpan = document.getElementById('sliderMaxValue');
            
            slider.noUiSlider.on('update', function(values, handle) {
                if (handle === 0) {
                    minValueSpan.textContent = Math.round(values[0]);
                }
                if (handle === 1) {
                    maxValueSpan.textContent = Math.round(values[1]);
                }
                filterProducts();
            });
        }
    });

    let allProducts = [];

    async function loadProducts() {
        try {
            const response = await fetch('http://localhost/kickzone/api/products.php');
            const data = await response.json();
            if (data.success) {
                allProducts = data.products;
                initFilters();
                renderProducts(allProducts);
            }
        } catch (error) {
            console.error('Ошибка:', error);
        }
    }

    function renderProducts(products) {
        const container = document.getElementById('catalog-products-container');
        if (!container) return;
        
        if (products.length === 0) {
            container.innerHTML = '<div class="card-product">Нет товаров</div>';
            return;
        }
        
        container.innerHTML = products.map(product => `
            <div class="card-product">
                <img src="/kickzone/uploads/products/${product.image || 'placeholder.jpg'}" style="width: 100%; height: 200px; object-fit: cover;">
                <h3>${product.name}</h3>
                <p>${Number(product.price).toLocaleString()} ₽</p>
                <button class="add-to-cart-btn" data-id="${product.id}"><i class="fa-solid fa-basket-shopping"></i></button>
            </div>
        `).join('');
        
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const productId = btn.dataset.id;
                const result = await addToCart(productId, 1);
                alert(result.success ? 'Товар добавлен в корзину' : 'Ошибка: ' + result.message);
            });
        });
    }

    function filterProducts() {
        const selectedBrands = Array.from(document.querySelectorAll('.brend-card input:checked')).map(cb => cb.value);
        const selectedColors = Array.from(document.querySelectorAll('.color-card input:checked')).map(cb => cb.value);
        const selectedSizes = Array.from(document.querySelectorAll('.size-card input:checked')).map(cb => cb.value);
        
        const slider = document.getElementById('Slider');
        let minPrice = 2000;
        let maxPrice = 50000;
        if (slider && slider.noUiSlider) {
            const values = slider.noUiSlider.get();
            minPrice = parseFloat(values[0]);
            maxPrice = parseFloat(values[1]);
        }
        
        let filtered = [...allProducts];
        
        filtered = filtered.filter(p => parseFloat(p.price) >= minPrice && parseFloat(p.price) <= maxPrice);
        
        if (selectedBrands.length > 0) {
            filtered = filtered.filter(p => selectedBrands.includes(p.brand));
        }
        if (selectedColors.length > 0) {
            filtered = filtered.filter(p => selectedColors.includes(p.color));
        }
        if (selectedSizes.length > 0) {
            filtered = filtered.filter(p => {
                if (!p.sizes) return false;
                const productSizes = p.sizes.split(', ').map(s => s.trim());
                return productSizes.some(s => selectedSizes.includes(s));
            });
        }
        
        renderProducts(filtered);
    }

    function initFilters() {
        const brands = [...new Set(allProducts.map(p => p.brand).filter(b => b))];
        const colors = [...new Set(allProducts.map(p => p.color).filter(c => c))];
        const sizes = [...new Set(
            allProducts
                .flatMap(p => p.sizes ? p.sizes.split(', ').map(s => s.trim()) : [])
                .filter(s => s)
        )];
        
        const brandContainer = document.getElementById('brand-filters');
        const colorContainer = document.getElementById('color-filters');
        const sizeContainer = document.getElementById('size-filters');
        
        if (brandContainer) {
            brandContainer.innerHTML = brands.map(b => `<label><input type="checkbox" value="${b}" class="filter-checkbox"> ${b}</label>`).join('');
        }
        if (colorContainer) {
            colorContainer.innerHTML = colors.map(c => `<label><input type="checkbox" value="${c}" class="filter-checkbox"> ${c}</label>`).join('');
        }
        if (sizeContainer) {
            sizeContainer.innerHTML = sizes.sort((a,b) => a-b).map(s => `<label><input type="checkbox" value="${s}" class="filter-checkbox"> ${s}</label>`).join('');
        }
        
        document.querySelectorAll('.filter-checkbox').forEach(cb => {
            cb.addEventListener('change', filterProducts);
        });
    }

    document.addEventListener('DOMContentLoaded', loadProducts);
</script>