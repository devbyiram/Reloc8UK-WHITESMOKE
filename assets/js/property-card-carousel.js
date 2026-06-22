(function () {
	'use strict';

	function updateCarousel(carousel, index) {
		var track = carousel.querySelector('.property-card-carousel__track');
		var slides = carousel.querySelectorAll('.property-card-carousel__slide');
		var countEl = carousel.querySelector('.property-card-carousel__count');
		var total = slides.length;

		if (!track || total === 0) {
			return;
		}

		var safeIndex = ((index % total) + total) % total;
		carousel.dataset.index = String(safeIndex);
		track.style.transform = 'translateX(-' + (safeIndex * 100) + '%)';

		if (countEl) {
			countEl.textContent = (safeIndex + 1) + '/' + total;
		}
	}

	function initCarousel(carousel) {
		var slides = carousel.querySelectorAll('.property-card-carousel__slide');
		var prevBtn = carousel.querySelector('.property-card-carousel__nav--prev');
		var nextBtn = carousel.querySelector('.property-card-carousel__nav--next');
		var total = slides.length;

		if (total <= 1) {
			if (prevBtn) {
				prevBtn.style.display = 'none';
			}
			if (nextBtn) {
				nextBtn.style.display = 'none';
			}
			updateCarousel(carousel, 0);
			return;
		}

		updateCarousel(carousel, 0);

		if (prevBtn) {
			prevBtn.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				var current = parseInt(carousel.dataset.index || '0', 10);
				updateCarousel(carousel, current - 1);
			});
		}

		if (nextBtn) {
			nextBtn.addEventListener('click', function (event) {
				event.preventDefault();
				event.stopPropagation();
				var current = parseInt(carousel.dataset.index || '0', 10);
				updateCarousel(carousel, current + 1);
			});
		}

		var viewport = carousel.querySelector('.property-card-carousel__viewport');
		if (!viewport) {
			return;
		}

		var touchStartX = 0;
		var touchDeltaX = 0;

		viewport.addEventListener('touchstart', function (event) {
			if (!event.changedTouches || !event.changedTouches.length) {
				return;
			}
			touchStartX = event.changedTouches[0].clientX;
			touchDeltaX = 0;
		}, { passive: true });

		viewport.addEventListener('touchmove', function (event) {
			if (!event.changedTouches || !event.changedTouches.length) {
				return;
			}
			touchDeltaX = event.changedTouches[0].clientX - touchStartX;
		}, { passive: true });

		viewport.addEventListener('touchend', function () {
			if (Math.abs(touchDeltaX) < 40) {
				return;
			}
			var current = parseInt(carousel.dataset.index || '0', 10);
			if (touchDeltaX < 0) {
				updateCarousel(carousel, current + 1);
			} else {
				updateCarousel(carousel, current - 1);
			}
		});
	}

	function initAll() {
		document.querySelectorAll('.property-card-carousel').forEach(initCarousel);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initAll);
	} else {
		initAll();
	}
})();
