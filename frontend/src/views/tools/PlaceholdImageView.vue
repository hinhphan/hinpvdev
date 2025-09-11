<script setup>
import { Button } from '@/components/ui/button';
import {
  Card,
  CardHeader,
  CardTitle,
  CardContent,
} from '@/components/ui/card'
import { FormControl, FormField, FormItem, FormLabel } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Loader2 } from 'lucide-vue-next';
import { useForm } from 'vee-validate';
import { ref, watch } from 'vue';
import { toolApi } from '@/api/toolApi';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { useClipboard } from '@vueuse/core';
import { useTitle } from '@vueuse/core'
import { Checkbox } from '@/components/ui/checkbox';
import { useHandleApiError } from '@/composables/handleApiError';

// Common
useTitle("Placehold Image Generator")

const imgUrl = ref(null)
const { copy, isSupported } = useClipboard({ imgUrl })
const { setError, errors } = useHandleApiError()

watch(errors.value, () => {
  form.setErrors(errors.value)
})

// Sumit Form
const isSubmit = ref(false)
const form = useForm({
  initialValues: {
    width: 200,
    height: 200,
    text: '200 x 200',
    color: '#FFFFFF',
    bg: '#000000',
    is_use_size_as_text: true,
  },
})

watch([
  () => form.values.width, 
  () => form.values.height, 
  () => form.values.is_use_size_as_text
], ([newWidth, newHeight]) => {
  if (form.values.is_use_size_as_text) {
    form.setValues({
      text: newWidth + ' x ' + newHeight,
    })
  }
})

const onSubmit = form.handleSubmit(async (values) => {
  isSubmit.value = true

  try {
    const res = await toolApi.createPlaceholdImage(values)
    const resData = res.data
    const placeholdImage = resData.data.placehold_image

    imgUrl.value = placeholdImage.url

  } catch (error) {
    setError(error)

  } finally {
    isSubmit.value = false
  }
})

// Random Placehold Image
const isRandom = ref(false)

const onRandom = async () => {
  isRandom.value = true
  form.setValues({
    is_use_size_as_text: false,
  })

  try {
    const res = await toolApi.getRandomPlaceholdImage()
    const resData = res.data
    const placeholdImage = resData.data.placehold_image

    form.setValues(placeholdImage.params_formatted)
    imgUrl.value = placeholdImage.url

  } catch (error) {
    setError(error)

  } finally {
    isRandom.value = false
  }
}

</script>

<template>
  <div class="h-screen flex justify-center items-start py-10 px-5">
    <Card class="w-[700px]">
      <CardHeader>
        <CardTitle>Placehold Image</CardTitle>
      </CardHeader>

      <CardContent>
        <form @submit="onSubmit">
          <div class="grid grid-cols-[110px_auto] items-center mb-3">
            <label>Size: </label>
            <div class="flex items-center gap-3">
              <FormField v-slot="{ componentField }" name="width">
                <FormItem class="max-w-40">
                  <div class="flex items-center gap-3">
                    <FormControl>
                      <Input type="number" v-bind="componentField" />
                    </FormControl>
                  </div>
                </FormItem>
              </FormField>
              <span>x</span>
              <FormField v-slot="{ componentField }" name="height">
                <FormItem class="max-w-40">
                  <div class="flex items-center gap-3">
                    <FormControl>
                      <Input type="number" v-bind="componentField" />
                    </FormControl>
                  </div>
                </FormItem>
              </FormField>
              <span>px</span>
            </div>
          </div>

          <div class="grid grid-cols-[110px_auto] items-center mb-3">
            <label>Text: </label>
            <div class="flex items-center gap-3">
              <FormField v-slot="{ componentField }" name="text">
                <FormItem class="grow">
                  <div class="flex items-center gap-3">
                    <FormControl>
                      <Input type="text" v-bind="componentField" :disabled="form.values.is_use_size_as_text" />
                    </FormControl>
                  </div>
                </FormItem>
              </FormField>
              <FormField v-slot="{ componentField }" name="color">
                <FormItem class="w-[100px]">
                  <div class="flex items-center gap-3">
                    <FormControl>
                      <Input type="color" v-bind="componentField" />
                    </FormControl>
                  </div>
                </FormItem>
              </FormField>
            </div>
          </div>

          <div class="grid grid-cols-[110px_auto] items-center mb-3">
            <label></label>
            <FormField v-slot="{ value, handleChange }" type="checkbox" name="is_use_size_as_text">
              <FormItem class="flex flex-row items-start gap-x-3 space-y-0">
                <FormControl>
                  <Checkbox :model-value="value" @update:model-value="handleChange" />
                </FormControl>
                <div class="space-y-1 leading-none">
                  <FormLabel>Use the size as text</FormLabel>
                </div>
              </FormItem>
            </FormField>
          </div>

          <div class="grid grid-cols-[110px_auto] items-center">
            <label>Background: </label>
            <FormField v-slot="{ componentField }" name="bg">
              <FormItem class="w-[100px]">
                <div class="flex items-center gap-3">
                  <FormControl>
                    <Input type="color" v-bind="componentField" />
                  </FormControl>
                </div>
              </FormItem>
            </FormField>
          </div>

          <div class="flex items-center justify-center mt-6 gap-3">
            <Button type="submit" :disabled="isSubmit">
              <Loader2 class="w-4 h-4 mr-1 animate-spin" v-if="isSubmit" />
              Generate
            </Button>

            <Button type="button" :disabled="isRandom" @click="onRandom">
              <Loader2 class="w-4 h-4 mr-1 animate-spin" v-if="isRandom" />
              Random
            </Button>
          </div>
        </form>

        <div v-if="imgUrl" class="mt-6">
          <div class="flex items-center gap-3 mb-3">
            <Input v-model="imgUrl" readonly />
            <Popover v-if="isSupported">
              <PopoverTrigger as-child>
                <Button type="button" @click="copy(imgUrl)">
                  Copy
                </Button>
              </PopoverTrigger>
              <PopoverContent class="w-fit" side="top">
                Copied!
              </PopoverContent>
            </Popover>
          </div>
          <div class="border rounded-sm p-2 flex justify-center items-center mb-3">
            <img :src="imgUrl" alt="PlaceholdImage" class="max-h-[400px] w-auto">
          </div>
          <div class="flex justify-end">
            <Button as-child="">
              <a :href="imgUrl" target="_blank">
                Download
              </a>
            </Button>
          </div>
        </div>
      </CardContent>
    </Card>
  </div>
</template>
