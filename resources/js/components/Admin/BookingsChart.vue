<template>
  <div class="relative h-48">
    <canvas ref="canvas" class="w-full h-full"></canvas>
    <p v-if="!data?.length" class="absolute inset-0 flex items-center justify-center text-gray-400 text-sm">
      No data available
    </p>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
  data: { type: Array, default: () => [] },
})

const canvas = ref(null)
let chart    = null

async function renderChart() {
  if (!canvas.value || !props.data?.length) return

  // Dynamically import Chart.js to avoid SSR issues
  const { Chart, registerables } = await import('chart.js')
  Chart.register(...registerables)

  if (chart) chart.destroy()

  const labels = props.data.map(d => {
    const date = new Date(d.date)
    return date.toLocaleDateString('en-IN', { day: 'numeric', month: 'short' })
  })
  const values = props.data.map(d => d.count)

  chart = new Chart(canvas.value, {
    type: 'bar',
    data: {
      labels,
      datasets: [{
        label: 'Bookings',
        data: values,
        backgroundColor: 'rgba(59,130,246,0.8)',
        borderRadius: 4,
        borderSkipped: false,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            title: (items) => items[0].label,
            label: (item) => ` ${item.raw} bookings`,
          },
        },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { maxTicksLimit: 7, font: { size: 11 }, color: '#9ca3af' },
        },
        y: {
          beginAtZero: true,
          grid: { color: '#f3f4f6' },
          ticks: { stepSize: 1, font: { size: 11 }, color: '#9ca3af' },
        },
      },
    },
  })
}

onMounted(renderChart)
watch(() => props.data, renderChart, { deep: true })
</script>