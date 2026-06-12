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
                <div class="brend-card"></div>
                <div class="size">Размер</div>
                <div class="size-card"></div>
                <div class="color">Цвет</div>
                <div class="color-card"></div>
            </div>

            <div class="card-container">
                <div class="group-product">
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                    <div class="card-product"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('Slider');
        
        if (slider && window.noUiSlider) {
            noUiSlider.create(slider, {
                start: [2000, 50000],           // Начальные значения (минимум и максимум)
                connect: true,               // Соединяет область между двумя ползунками
                step: 500,                   // Шаг переключения
                range: {                     // Диапазон возможных значений
                    'min': 2000,
                    'max': 50000
                },
                format: {                    // Формат отображения чисел
                    to: value => Math.round(value),
                    from: value => Math.round(value)
                }
            });
            
            // Элементы для отображения текущих значений
            const minValueSpan = document.getElementById('sliderMinValue');
            const maxValueSpan = document.getElementById('sliderMaxValue');
            
            // Обновляем значения на странице при каждом изменении ползунка
            slider.noUiSlider.on('update', function(values, handle) {
                if (handle === 0) {
                    minValueSpan.textContent = Math.round(values[0]);
                }
                if (handle === 1) {
                    maxValueSpan.textContent = Math.round(values[1]);
                }
            });
        }
    });
</script>