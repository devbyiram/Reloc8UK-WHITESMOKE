(function () {
	'use strict';

	function getImageSources(carousel) {
		var sources = [];
		carousel.querySelectorAll('.property-card-carousel__source').forEach(function (source) {
			var src = source.getAttribute('data-src');
			if (src) {
				sources.push(src);
			}
		});
		return sources;
	}

	function setPaneImage(pane, src) {
		if (!pane || !src) {
			return;
		}
		pane.style.backgroundImage = 'url("' + src + '")';
	}

	function updateCarousel(carousel, index) {
		var sources = getImageSources(carousel);
		var total = sources.length;
		var mainPane = carousel.querySelector('.property-card-carousel__pane--main .property-card-image');
		var thumbPanes = carousel.querySelectorAll('.property-card-carousel__thumb .property-card-image');
		var thumbsWrap = carousel.querySelector('.property-card-carousel__pane--thumbs');
		var countEl = carousel.querySelector('.property-card-carousel__count');
		var prevBtn = carousel.querySelector('.property-card-carousel__nav--prev');
		var nextBtn = carousel.querySelector('.property-card-carousel__nav--next');

		if (!total) {
			return;
		}

		var safeIndex = ((index % total) + total) % total;
		carousel.dataset.index = String(safeIndex);

		setPaneImage(mainPane, sources[safeIndex]);

		if (total === 1) {
			if (thumbsWrap) {
				thumbsWrap.style.display = 'none';
			}
			if (prevBtn) {
				prevBtn.style.display = 'none';
			}
			if (nextBtn) {
				nextBtn.style.display = 'none';
			}
		} else {
			if (thumbsWrap) {
				thumbsWrap.style.display = '';
			}
			if (prevBtn) {
				prevBtn.style.display = '';
			}
			if (nextBtn) {
				nextBtn.style.display = '';
			}

			if (thumbPanes[0]) {
				setPaneImage(thumbPanes[0], sources[(safeIndex + 1) % total]);
				thumbPanes[0].closest('.property-card-carousel__thumb').style.display = '';
			}

			if (thumbPanes[1]) {
				if (total >= 3) {
					setPaneImage(thumbPanes[1], sources[(safeIndex + 2) % total]);
					thumbPanes[1].closest('.property-card-carousel__thumb').style.display = '';
				} else {
					thumbPanes[1].closest('.property-card-carousel__thumb').style.display = 'none';
				}
			}
		}

		if (countEl) {
			countEl.textContent = (safeIndex + 1) + '/' + total;
		}

		carousel.dispatchEvent(new CustomEvent('propertyCarouselChange', {
			bubbles: true,
			detail: { index: safeIndex }
		}));
	}

	function initCarousel(carousel) {
		var sources = getImageSources(carousel);
		var prevBtn = carousel.querySelector('.property-card-carousel__nav--prev');
		var nextBtn = carousel.querySelector('.property-card-carousel__nav--next');
		var mainPane = carousel.querySelector('.property-card-carousel__pane--main');

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

		if (!mainPane || sources.length <= 1) {
			return;
		}

		var touchStartX = 0;
		var touchDeltaX = 0;

		mainPane.addEventListener('touchstart', function (event) {
			if (!event.changedTouches || !event.changedTouches.length) {
				return;
			}
			touchStartX = event.changedTouches[0].clientX;
			touchDeltaX = 0;
		}, { passive: true });

		mainPane.addEventListener('touchmove', function (event) {
			if (!event.changedTouches || !event.changedTouches.length) {
				return;
			}
			touchDeltaX = event.changedTouches[0].clientX - touchStartX;
		}, { passive: true });

		mainPane.addEventListener('touchend', function () {
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
