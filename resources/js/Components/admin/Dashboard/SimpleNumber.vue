<template>
    <div class="simpleNumber grid grid-cols-[auto,5rem] justify-between
            border py-4 px-3 rounded-xl bg-gray-50">
        <div class="left grid gap-4">
            <div>
                <h5 class="text-gray-500 text-sm">Total Sales</h5>
                <strong>$559.25k</strong>
            </div>
            <p class="text-gray-500 text-sm">$435.25k last month</p>
        </div>
        <div class="right relative">
            <Doughnut :data="chartData"/>
            <span class="text-gray-700 absolute top-[50%] left-[50%] translate-x-[-45%] translate-y-[-25%]">80%</span>
        </div>
    </div>
</template>

<script setup>
import { Doughnut } from 'vue-chartjs'
import {ref} from "vue";

import {
    Chart as ChartJS,
    LinearScale,
    ArcElement
} from 'chart.js'
ChartJS.register(LinearScale, ArcElement)


const chartData = ref({
    datasets: [{
        data: [80, 20],
        backgroundColor: [
            // '#ed0503',
            '#3971f9',
            '#d6e4fe',
        ],
        cutout: '75%',
        // borderRadius: 10,
        borderWidth: 0 // Removes the border
    }]
});

</script>

<style scoped>

</style>
