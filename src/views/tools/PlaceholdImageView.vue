<script setup>
import { Button } from '@/components/ui/button';
import {
  Card,
  CardHeader,
  CardTitle,
  CardContent,
} from '@/components/ui/card'
import { FormControl, FormField, FormItem, FormMessage } from '@/components/ui/form';
import { Input } from '@/components/ui/input';
import { Loader2 } from 'lucide-vue-next';
import { useForm } from 'vee-validate';
import { ref } from 'vue';
import { toolApi } from '@/api/toolApi';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { useClipboard } from '@vueuse/core';

const isSubmit = ref(false)
const imgUrl = ref(null)
const { copy, isSupported } = useClipboard({ imgUrl })

const form = useForm({
  initialValues: {
    width: 200,
    height: 200,
    text: '',
    color: '#000000',
    bg: '#FFFFFF',
  },
})

const onSubmit = form.handleSubmit(async (values) => {
  isSubmit.value = true

  try {
    const res = await toolApi.createPlaceholdImage(values)
    const resData = res.data
    const placeholdImage = resData.data.placehold_image

    imgUrl.value = placeholdImage.url

  } catch (error) {
    console.log(error);

  } finally {
    isSubmit.value = false
  }
})

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
                  <FormMessage />
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
                  <FormMessage />
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
                      <Input type="text" v-bind="componentField" />
                    </FormControl>
                  </div>
                  <FormMessage />
                </FormItem>
              </FormField>
              <FormField v-slot="{ componentField }" name="color">
                <FormItem class="w-[100px]">
                  <div class="flex items-center gap-3">
                    <FormControl>
                      <Input type="color" v-bind="componentField" />
                    </FormControl>
                  </div>
                  <FormMessage />
                </FormItem>
              </FormField>
            </div>
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
                <FormMessage />
              </FormItem>
            </FormField>
          </div>

          <div class="flex justify-center mt-6">
            <Button type="submit" :disabled="isSubmit">
              <Loader2 class="w-4 h-4 mr-1 animate-spin" v-if="isSubmit" />
              Generate
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
          <div class="border rounded-sm p-2 flex justify-center items-center">
            <img :src="imgUrl" alt="PlaceholdImage" class="max-h-[400px] w-auto">
          </div>
        </div>
      </CardContent>
    </Card>
  </div>
</template>
