<template>
  <div class="modal-product">
    <a-modal :open="open" :title="title" :footer="null" @cancel="handleCancelModal">
      <a-form
        :model="formState"
        name="basic"
        autocomplete="off"
        @finish="onFinish"
        @finish-failed="onFinishFailed"
      >
        <a-form-item
          label="Title"
          name="title"
          :rules="[{ required: true, message: 'Please input your title' }]"
        >
          <a-input v-model:value="formState.title" />
        </a-form-item>
        <a-form-item
          label="Discount Percentage"
          name="discountPercentage"
          :rules="[{ required: true, message: 'Please input your discountPercentage' }]"
        >
          <a-input-number
            v-model:value="formState.discountPercentage"
            :min="0"
            :step="0.001"
            style="width: 100%"
          />
        </a-form-item>
        <a-form-item
          label="Price"
          name="price"
          :rules="[{ required: true, message: 'Please input your price' }]"
        >
          <a-input-number v-model:value="formState.price" style="width: 100%" />
        </a-form-item>
        <a-form-item
          label="Rating"
          name="rating"
          :rules="[{ required: true, message: 'Please input your rating' }]"
        >
          <a-input-number v-model:value="formState.rating" style="width: 100%" />
        </a-form-item>
        <a-form-item
          label="Stock"
          name="stock"
          :rules="[{ required: true, message: 'Please input your stock' }]"
        >
          <a-input-number v-model:value="formState.stock" style="width: 100%" />
        </a-form-item>
        <a-form-item
          label="Brand"
          name="brand"
          :rules="[{ required: true, message: 'Please input your brand' }]"
        >
          <a-input v-model:value="formState.brand" style="width: 100%" />
        </a-form-item>
        <a-form-item
          label="Category"
          name="category"
          :rules="[{ required: true, message: 'Please input your category' }]"
        >
          <a-input v-model:value="formState.category" style="width: 100%" />
        </a-form-item>
        <a-form-item
          label="Thumbnail"
          name="thumbnail"
          :rules="[{ required: true, message: 'Please input your thumbnail' }]"
        >
          <a-input v-model:value="formState.thumbnail" style="width: 100%" />
        </a-form-item>
        <a-form-item label="Description" name="description">
          <a-input v-model:value="formState.description" style="width: 100%" />
        </a-form-item>
        <div class="clearfix">
          <a-upload
            :file-list="fileList"
            :multiple="true"
            :before-upload="beforeUpload"
            @remove="handleRemove"
          >
            <a-button> Select File </a-button>
          </a-upload>
        </div>
        <a-form-item class="flex justify-center items-center mt-auto">
          <a-button type="primary" html-type="submit">{{ labelButton }}</a-button>
        </a-form-item>
      </a-form>
    </a-modal>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import type { UploadProps } from 'ant-design-vue'

interface FormState {
  id: number
  title: string
  description: string
  price: number
  discountPercentage: number
  rating: number
  stock: number
  brand: string
  category: string
  thumbnail: string
  images: []
}
export default defineComponent({
  name: 'ModalProduct',
  props: {
    open: { type: Boolean, default: false },
    isEdit: { type: Boolean },
    title: { type: String, default: 'Add new Product' },
    labelButton: { type: String, default: 'Submit' },
    productDetail: {
      type: Object,
      default: () => {
        return {
          id: 0,
          title: '',
          description: '',
          price: 0,
          discountPercentage: 0,
          rating: 0,
          stock: 0,
          brand: '',
          category: '',
          thumbnail: '',
          images: [],
        }
      },
    },
  },
  emits: ['handleSubmitModal', 'handleCancelModal', 'handleSubmitModal'],
  setup(props, context) {
    const formState = ref<FormState>({
      id: props.isEdit ? props.productDetail.id : 1,
      title: props.isEdit ? props.productDetail.title : '',
      description: props.isEdit ? props.productDetail.description : '',
      price: props.isEdit ? props.productDetail.price : 0,
      discountPercentage: props.isEdit ? props.productDetail.discountPercentage : 0.0,
      rating: props.isEdit ? props.productDetail.rating : 0.0,
      stock: props.isEdit ? props.productDetail.stock : 0,
      brand: props.isEdit ? props.productDetail.brand : '',
      category: props.isEdit ? props.productDetail.category : '',
      thumbnail: props.isEdit ? props.productDetail.thumbnail : '',
      images: [],
    })
    const fileList = ref<UploadProps['fileList']>([])
    const onFinish = () => {
      context.emit('handleSubmitModal', formState.value)
    }

    const onFinishFailed = (errorInfo) => {
      console.log('Failed:', errorInfo)
    }
    const beforeUpload: UploadProps['beforeUpload'] = (file) => {
      fileList.value = [...(fileList.value || []), file]
      formState.value.images = JSON.parse(JSON.stringify(fileList.value)) || []
      return false
    }
    const handleRemove: UploadProps['onRemove'] = (file) => {
      if (fileList.value?.length) {
        const index = fileList.value.indexOf(file)
        const newFileList = fileList.value.slice()
        newFileList.splice(index, 1)
        fileList.value = newFileList
      }
    }
    return {
      formState,
      fileList,
      beforeUpload,
      handleRemove,
      onFinish,
      onFinishFailed,
    }
  },
  methods: {
    handleCancelModal() {
      this.$emit('handleCancelModal', false)
    },
  },
})
</script>
