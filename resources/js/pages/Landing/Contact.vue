<template>
  <div class="min-h-screen bg-gray-50">
    <Head>
      <title>Contact Us - Chennai Smart Care</title>
      <meta name="description" content="Get in touch with Chennai Smart Care for expert AC, fridge, and washing machine repair services. Call us or visit our service center." />
    </Head>

    <AppHeader />

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 text-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl sm:text-5xl font-extrabold mb-4">Contact Us</h1>
        <p class="text-xl text-blue-100 max-w-2xl mx-auto">
          We're here to help with all your appliance repair needs
        </p>
      </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
          <!-- Contact Information -->
          <div>
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Get in Touch</h2>
            
            <div class="space-y-6">
              <div v-for="(item, index) in contactInfo" :key="index" 
                   class="flex items-start gap-4 p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 flex-shrink-0">
                  <component :is="item.icon" class="w-6 h-6" />
                </div>
                <div>
                  <h3 class="font-semibold text-gray-900">{{ item.title }}</h3>
                  <p v-if="item.type === 'link'">
                    <a :href="item.link" class="text-blue-600 hover:underline">{{ item.value }}</a>
                  </p>
                  <p v-else class="text-gray-600">{{ item.value }}</p>
                  <p v-if="item.subtext" class="text-sm text-gray-500 mt-1">{{ item.subtext }}</p>
                </div>
              </div>
            </div>

            <!-- Business Hours -->
            <div class="mt-8 bg-gray-50 rounded-xl p-6">
              <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <ClockIcon class="w-5 h-5 text-blue-600" />
                Business Hours
              </h3>
              <div class="space-y-2">
                <div v-for="hour in businessHours" :key="hour.day" 
                     class="flex justify-between text-sm">
                  <span class="text-gray-600">{{ hour.day }}</span>
                  <span class="font-medium text-gray-900">{{ hour.hours }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Contact Form -->
          <div class="bg-white rounded-2xl shadow-xl p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h2>
            
            <form @submit.prevent="submitForm" class="space-y-6">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                  <input type="text" v-model="form.firstName" required
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                  <input type="text" v-model="form.lastName" required
                         class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" v-model="form.email" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="tel" v-model="form.phone" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Service Needed</label>
                <select v-model="form.service" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                  <option value="">Select a service</option>
                  <option value="ac">AC Repair</option>
                  <option value="refrigerator">Refrigerator Repair</option>
                  <option value="washing-machine">Washing Machine Repair</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                <textarea v-model="form.message" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
              </div>

              <button type="submit"
                      :disabled="isSubmitting"
                      class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span v-if="isSubmitting">Sending...</span>
                <span v-else>Send Message</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Map Section -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Visit Our Service Center</h2>
        <div class="bg-white rounded-2xl overflow-hidden shadow-lg">
          <div class="h-96 bg-gray-200 flex items-center justify-center">
            <!-- Replace with actual Google Maps embed -->
            <div class="text-center text-gray-500">
              <MapIcon class="w-12 h-12 mx-auto mb-2" />
              <p>Google Maps Integration</p>
              <p class="text-sm">6/56, sindhu street , Ranga nagar, Porur, Moulivakkam, 600116</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import AppHeader from '@/components/Landing/AppHeader.vue'
import AppFooter from '@/components/Landing/AppFooter.vue'
import {
  PhoneIcon,
  EnvelopeIcon,
  MapPinIcon,
  ClockIcon,
  MapIcon
} from '@heroicons/vue/24/outline'

const contactInfo = [
  {
    icon: PhoneIcon,
    title: 'Phone',
    value: '+91 94449 00470',
    type: 'link',
    link: 'tel:+919444900470',
    subtext: 'Available 9AM - 9PM'
  },
  {
    icon: EnvelopeIcon,
    title: 'Email',
    value: 'support@chennaismartcare.com',
    type: 'link',
    link: 'mailto:support@chennaismartcare.com',
    subtext: 'We reply within 2 hours'
  },
  {
    icon: MapPinIcon,
    title: 'Service Center',
    value: '6/56, sindhu street , Ranga nagar, Porur, Moulivakkam, 600116',
    type: 'text',
    subtext: 'Branch'
  },
  {
    icon: MapPinIcon,
    title: 'Registered Office',
    value: '6/224, VBR Building, Kovur, Kanchipuram – 600128)',
    type: 'text',
    subtext: 'Main service center'
  }
]

const businessHours = [
  { day: 'Monday - Saturday', hours: '9:00 AM - 9:00 PM' },
  { day: 'Sunday', hours: '10:00 AM - 6:00 PM' },
  { day: 'Emergency Service', hours: '24/7 Available' }
]

const form = ref({
  firstName: '',
  lastName: '',
  email: '',
  phone: '',
  service: '',
  message: ''
})

const isSubmitting = ref(false)

async function submitForm() {
  isSubmitting.value = true
  try {
    // Add your form submission logic here
    await new Promise(resolve => setTimeout(resolve, 1500)) // Simulate API call
    alert('Message sent successfully!')
    form.value = {
      firstName: '',
      lastName: '',
      email: '',
      phone: '',
      service: '',
      message: ''
    }
  } catch (error) {
    alert('Failed to send message. Please try again.')
  } finally {
    isSubmitting.value = false
  }
}
</script>