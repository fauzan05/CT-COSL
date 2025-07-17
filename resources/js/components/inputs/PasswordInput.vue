<template>
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
            Password <span class="text-red-500">*</span>
        </label>
        <div class="relative flex gap-2">
            <div class="relative flex-1">
                <input :type="showPassword ? 'text' : 'password'" :value="modelValue"
                    @input="$emit('update:modelValue', $event.target.value)" placeholder="Enter password"
                    class="w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required>
                <button type="button" @click="showPassword = !showPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg v-if="!showPassword" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg v-else class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>
            <button @click="generatePassword"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Generate Password
            </button>
        </div>

        <!-- Password Requirements Checklist -->
        <div class="mt-3 space-y-2">
            <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Password Requirements:
            </div>
            <div v-for="(check, index) in passwordChecks" :key="index" class="flex items-center space-x-2 text-sm"
                :class="check.passed ? 'text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-600'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path v-if="check.passed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7" />
                    <circle v-else cx="12" cy="12" r="10" stroke-width="2" />
                </svg>
                <span>{{ check.message }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    modelValue: {
        type: String,
        required: true
    }
})

const emit = defineEmits(['update:modelValue'])
const showPassword = ref(false)

const passwordChecks = computed(() => [
    {
        message: 'Minimum 8 characters',
        passed: props.modelValue.length >= 8
    },
    {
        message: 'Contains uppercase letter',
        passed: /[A-Z]/.test(props.modelValue)
    },
    {
        message: 'Contains lowercase letter',
        passed: /[a-z]/.test(props.modelValue)
    },
    {
        message: 'Contains number',
        passed: /[0-9]/.test(props.modelValue)
    },
    {
        message: 'Contains special character',
        passed: /[!@#$%^&*]/.test(props.modelValue)
    }
])

const generatePassword = () => {
    const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
    const lowercase = 'abcdefghijklmnopqrstuvwxyz'
    const numbers = '0123456789'
    const special = '!@#$%^&*'
    const allChars = uppercase + lowercase + numbers + special

    let generatedPassword = ''
    // Ensure at least one of each required character type
    generatedPassword += uppercase[Math.floor(Math.random() * uppercase.length)]
    generatedPassword += lowercase[Math.floor(Math.random() * lowercase.length)]
    generatedPassword += numbers[Math.floor(Math.random() * numbers.length)]
    generatedPassword += special[Math.floor(Math.random() * special.length)]

    // Fill the rest with random characters
    for (let i = 0; i < 4; i++) {
        generatedPassword += allChars[Math.floor(Math.random() * allChars.length)]
    }

    // Shuffle the password
    const shuffledPassword = generatedPassword
        .split('')
        .sort(() => Math.random() - 0.5)
        .join('')

    emit('update:modelValue', shuffledPassword)
}
</script>