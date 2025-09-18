<template>
    <div class="">
        <Line :data="chartData" />
    </div>
</template>

<script setup>
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js'
import { Line } from 'vue-chartjs'
import {ref} from "vue";

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
)

const chartData = ref({
    labels: ["January", "February", "March", "April", "May", "June", "July"],
    datasets: [
        {
            label: 'Views',
            data: [12, 43, 7, 56, 89, 2, 15],
            fill: false,
            borderColor: '#6956e5',
            tension: 0.1
        },
        {
            label: 'Downloads',
            data: [34, 76, 22, 18, 53, 45, 68],
            fill: false,
            borderColor: '#fb896a',
            tension: 0.1
        },
        {
            label: 'purchases',
            data: [89, 17, 41, 63, 2, 95, 28],
            fill: false,
            borderColor: '#f9c081',
            tension: 0.1
        },
        {
            label: 'Revenue',
            data: [56, 84, 62, 7, 11, 94, 37],
            fill: false,
            borderColor: '#a5d2f2',
            tension: 0.1
        },
    ]
});

</script>

<style scoped>

</style>
