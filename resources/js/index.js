import "flatpickr/dist/flatpickr.min.css";
import "dropzone/dist/dropzone.css";
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/free-mode';
import 'swiper/css/mousewheel';
import "../css/style.css";

import Alpine from "alpinejs";
import persist from "@alpinejs/persist";
import flatpickr from "flatpickr";
import Dropzone from "dropzone";
import { createIcons, icons } from "lucide";
import ApexCharts from "apexcharts";
window.ApexCharts = ApexCharts;

// Swiper imports
import Swiper from 'swiper';
import { Navigation, Pagination, FreeMode, Mousewheel } from 'swiper/modules';

// make Swiper available globally
window.initAllSwipers = () => {
  const swipers = document.querySelectorAll('.swiper-init');

  swipers.forEach((el) => {
      // read data attributes
      const space = parseInt(el.dataset.space) || 16;
      const freeMode = el.dataset.freeMode === 'true';
      const mousewheel = el.dataset.mousewheel === 'true';

      // slides per view defaults
      const slidesDefault = parseFloat(el.dataset.slidesDefault) || 1.2;
      const slidesSm = el.dataset.slidesSm ? parseFloat(el.dataset.slidesSm) : null;
      const slidesMd = el.dataset.slidesMd ? parseFloat(el.dataset.slidesMd) : null;
      const slidesLg = el.dataset.slidesLg ? parseFloat(el.dataset.slidesLg) : null;
      const slidesXl = el.dataset.slidesXl ? parseFloat(el.dataset.slidesXl) : null;

      // Build breakpoints object only for provided data attributes
      const breakpoints = {};
      if (slidesSm !== null) breakpoints[480] = { slidesPerView: slidesSm };
      if (slidesMd !== null) breakpoints[640] = { slidesPerView: slidesMd };
      if (slidesLg !== null) breakpoints[768] = { slidesPerView: slidesLg };
      if (slidesXl !== null) breakpoints[1024] = { slidesPerView: slidesXl };

      new Swiper(el, {
          modules: [Navigation, Pagination, FreeMode, Mousewheel],
          slidesPerView: slidesDefault,
          spaceBetween: space,
          freeMode: freeMode,
          mousewheel: mousewheel,
          pagination: {
              el: el.querySelector('.swiper-pagination'),
              clickable: true,
          },
          navigation: {
              nextEl: el.querySelector('.swiper-button-next'),
              prevEl: el.querySelector('.swiper-button-prev'),
          },
          breakpoints: breakpoints,
      });
  });
};

Alpine.plugin(persist);
window.Alpine = Alpine;
Alpine.start();

// Init flatpickr
flatpickr(".datepicker", {
  mode: "range",
  static: true,
  monthSelectorType: "static",
  dateFormat: "M j, Y",
  defaultDate: [new Date().setDate(new Date().getDate() - 6), new Date()],
  prevArrow:
    '<svg class="stroke-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.25 6L9 12.25L15.25 18.5" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
  nextArrow:
    '<svg class="stroke-current" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.75 19L15 12.75L8.75 6.5" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
  onReady: (selectedDates, dateStr, instance) => {
    // eslint-disable-next-line no-param-reassign
    instance.element.value = dateStr.replace("to", "-");
    const customClass = instance.element.getAttribute("data-class");
    instance.calendarContainer.classList.add(customClass);
  },
  onChange: (selectedDates, dateStr, instance) => {
    // eslint-disable-next-line no-param-reassign
    instance.element.value = dateStr.replace("to", "-");
  },
});

// Init Dropzone
const dropzoneArea = document.querySelectorAll("#demo-upload");

if (dropzoneArea.length) {
  let myDropzone = new Dropzone("#demo-upload", { url: "/file/post" });
}

// Get the current year
const year = document.getElementById("year");
if (year) {
  year.textContent = new Date().getFullYear();
}

window.renderLucideIcons = () => createIcons({ icons });

document.addEventListener("DOMContentLoaded", () => {
  renderLucideIcons();
});