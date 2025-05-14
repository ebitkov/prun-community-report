import ChartJsController from './symfony_chartjs_controller.js';
import {Chart} from "chart.js";

export default class extends ChartJsController {
    viewValueChanged(newConfig, oldConfig) {
        if (oldConfig.type && newConfig.type !== oldConfig.type) {
            this.chart.destroy();
            this.chart = new Chart(this.element, newConfig);
            return;
        }
        super.viewValueChanged(newConfig, oldConfig);
    }
}