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

  const { Chart, registerables } = await import('chart.js')
  Chart.register(...registerables)

  if (chart) chart.destroy()

  const labels = props.data.map(d => {
    const [year, month] = d.month.split('-')
    return new Date(year, month - 1).toLocaleDateString('en-IN', { month: 'short', year: '2-digit' })
  })
  const values = props.data.map(d => Math.round(d.revenue))

  chart = new Chart(canvas.value, {
    type: 'line',
    data: {
      labels,
      datasets: [{
        label: 'Revenue (₹)',
        data: values,
        borderColor: '#10b981',
        backgroundColor: 'rgba(16,185,129,0.1)',
        borderWidth: 2.5,
        pointBackgroundColor: '#10b981',
        pointRadius: 4,
        pointHoverRadius: 6,
        tension: 0.4,
        fill: true,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: (item) => ` ₹${new Intl.NumberFormat('en-IN').format(item.raw)}`,
          },
        },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { size: 11 }, color: '#9ca3af' },
        },
        y: {
          beginAtZero: true,
          grid: { color: '#f3f4f6' },
          ticks: {
            font: { size: 11 },
            color: '#9ca3af',
            callback: (v) => '₹' + new Intl.NumberFormat('en-IN', { notation: 'compact' }).format(v),
          },
        },
      },
    },
  })
}

onMounted(renderChart)
watch(() => props.data, renderChart, { deep: true })
</script>