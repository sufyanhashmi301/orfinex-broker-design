import 'swiper/css/bundle';
import "../css/style.css";

import Alpine from "alpinejs";
import persist from "@alpinejs/persist";
import { createIcons, icons } from "lucide";
import ApexCharts from "apexcharts";

import Swiper from "swiper";
import { Navigation, Pagination } from 'swiper/modules';

// Register Swiper modules globally
Swiper.use([Navigation, Pagination]);

window.Swiper = Swiper;
window.ApexCharts = ApexCharts;

Alpine.plugin(persist);
window.Alpine = Alpine;
Alpine.start();


window.renderLucideIcons = () => createIcons({ icons });

document.addEventListener("DOMContentLoaded", () => {
  renderLucideIcons();
});