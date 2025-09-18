<template>
    <div class="">
        <Pie :data="chartData" />
    </div>
</template>

<script setup>
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js'
import {Pie} from 'vue-chartjs'
import {ref} from "vue";

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

const chartData = ref({
    labels: [
        'Chrome',
        'Safari',
        'Firefox',
        'Edge'
    ],
    datasets: [{
        label: 'Views count',
        data: [300, 50, 100, 70],
        backgroundColor: [
            '#6956e5',
            '#fb896a',
            '#f9c081',
            '#a5d2f2',
        ],
        hoverOffset: 4,
    }]
});

</script>

<style scoped>

</style>
