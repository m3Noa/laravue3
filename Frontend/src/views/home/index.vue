<template>
  <div class="p-8">
    <div class="flex items-center justify-end mb-2">
      <a-button @click="handleAddNewProduct"> Add new Product </a-button>
    </div>
    <a-table
      :pagination="false"
      :columns="TABLE_COLUMN"
      :data-source="productList?.products"
      bordered
    >
      <template #bodyCell="{ column, text, record }">
        <a-skeleton
          v-if="isLoading && column.dataIndex !== 'thumbnail'"
          size="small"
          :paragraph="{ rows: 2 }"
        />
        <template v-if="column.dataIndex === 'thumbnail'">
          <a-skeleton-image v-if="isLoading" :size="size" :shape="avatarShape" />
          <img v-else class="w-[100px] h-[100px] object-contain" :src="record.thumbnail" alt="" />
        </template>
        <template v-if="column.dataIndex === 'price' && !isLoading">
          {{ formatPrice(text) }}$
        </template>
        <template v-if="column.dataIndex === 'discountPercentage' && !isLoading">
          {{ text }}%
        </template>
        <template v-else-if="column.dataIndex === 'action' && !isLoading">
          <div class="flex">
            <!-- <router-link
              v-if="!editableData[record.id]"
              class="mr-2"
              :to="`/product/detail?slug=${record.id}`"
              >Detail</router-link
            > -->
            <div class="mr-2">
              <span v-if="editableData[record.id]">
                <a-typography-link class="mr-2" @click="save(record.id)">Save</a-typography-link>
                <a-popconfirm title="Sure to cancel?" @confirm="cancel(record.id)">
                  <a>Cancel</a>
                </a-popconfirm>
              </span>
              <span v-else>
                <a @click="edit(record)">Edit</a>
              </span>
            </div>
            <div v-if="!editableData[record.id]">
              <a-popconfirm
                placement="topRight"
                title="Sure to delete?"
                @confirm="onDelete(record.id)"
              >
                <a>Delete</a>
              </a-popconfirm>
            </div>
          </div>
        </template>
      </template>
    </a-table>
    <a-pagination
      v-if="pagination?.total"
      v-model="pagination.skip"
      class="mt-2"
      :show-size-changer="false"
      :total="pagination?.total"
      @change="onChangePage"
    />
    <modal-product
      v-if="isOpenModal"
      :open="isOpenModal"
      :title="titleModal"
      :product-detail="productDetail"
      :label-button="labelButton"
      :is-edit="isEdit"
      @handle-submit-modal="handleSubmitModal"
      @handle-cancel-modal="handleCancelModal"
    />
  </div>
</template>
<script lang="ts" setup>
import { reactive, ref } from 'vue'
import type { UnwrapRef } from 'vue'
import { message } from 'ant-design-vue'
import ProductService from '../../services/ProductService'
import ModalProduct from './components/ModalProduct.vue'
import { PAGINATION, TABLE_COLUMN } from '@/constants/views/home/index'

interface ProductItem {
  id?: number
  title: string
  description: string
  price: number
  discountPercentage: number
  rating: number
  stock: number
  brand: string
  category: string
  thumbnail: string
  images: string[]
}
interface Pagination {
  skip: number | null
  limit: number
  total: number | null
}

const isLoading = ref<boolean>(false)
const productList = ref<ProductItem[] | null>(null)
const labelButton = ref<string>('Submit')
const isEdit = ref<boolean>(false)
const productDetail = ref<ProductItem>({
  id: 0,
  title: '',
  description: '',
  price: 0,
  discountPercentage: 0.0,
  rating: 0.0,
  stock: 0,
  brand: '',
  category: '',
  thumbnail: '',
  images: [],
})
const pagination = ref<Pagination>({
  skip: null,
  limit: PAGINATION.Limit,
  total: null,
})
const isOpenModal = ref<boolean>(false)
const titleModal = ref<string>('Add new a Product')

const editableData: UnwrapRef<Record<string, ProductItem>> = reactive({})

const getProductAll = async (page?: number) => {
  const params = {
    limit: pagination.value.limit,
    skip: page || pagination.value.skip,
  }
  isLoading.value = true
  const res = await ProductService.getProductAll(params)
  isLoading.value = false
  if (res.success) {
    productList.value = res
    const { skip, limit, total } = res
    pagination.value = {
      skip,
      limit,
      total,
    }
    message.success({ content: 'Get product list successfully', key: 'get', duration: 2 })
  } else {
    message.error({ content: 'Get product list error', key: 'get', duration: 2 })
  }
}
getProductAll()

const onDelete = async (id: number) => {
  isLoading.value = true
  const res = await ProductService.deleteProduct(id)
  isLoading.value = false
  if (res.success) {
    message.success({ content: 'Delete product list successfully', key: 'delete', duration: 2 })
    getProductAll(1)
  } else {
    message.error({ content: 'Delete product list error', key: 'delete', duration: 2 })
  }
}
const onChangePage = (e: number) => {
  getProductAll(e)
}

const edit = (record: ProductItem) => {
  isEdit.value = true
  const data = JSON.parse(JSON.stringify(record))
  isOpenModal.value = true
  titleModal.value = 'Edit Product'
  labelButton.value = 'Save'
  productDetail.value = {
    id: data.id,
    title: data.title,
    description: data.description,
    price: data.price || 0,
    discountPercentage: data.discountPercentage,
    rating: data.rating || 0.0,
    stock: data.stock || 0.0,
    brand: data.brand,
    category: data.category,
    thumbnail: data.thumbnail,
    images: [],
  }
}
const save = (id: number) => {
  console.log(id)
}
const cancel = (id: number) => {
  delete editableData[id]
}
const formatPrice = (price: number) => {
  return price
    .toString()
    .replace(/\B(?=(\d{3})+(?!\d))/g, ',')
    .replace('.00', '')
}
const handleAddNewProduct = () => {
  isOpenModal.value = true
  titleModal.value = 'Add new Product'
  labelButton.value = 'Submit'
  isEdit.value = false
}

const handleSubmitModal = async (value: ProductItem) => {
  const formData = JSON.parse(JSON.stringify(value))
  const payload = {
    title: formData.title,
    description: formData.description,
    price: formData.price,
    discountPercentage: formData.discountPercentage,
    rating: formData.rating,
    stock: formData.stock,
    brand: formData.brand,
    category: formData.category,
    thumbnail: formData.thumbnail,
    images: [],
  }
  isLoading.value = true
  if (isEdit.value) {
    const res = await ProductService.updateProduct({ id: formData.id, payload })
    isLoading.value = false
    isOpenModal.value = false
    isEdit.value = false
    if (res.success) {
      message.success({ content: 'Update product successfully', key: 'update', duration: 2 })
      getProductAll()
    } else {
      message.error({ content: 'Update product error', key: 'update', duration: 2 })
    }
  } else {
    const res = await ProductService.createProduct(payload)
    isLoading.value = false
    isOpenModal.value = false
    isEdit.value = false
    if (res.success) {
      message.success({ content: 'Create new product successfully', key: 'add', duration: 2 })
      getProductAll()
    } else {
      message.error({ content: 'Create new product error', key: 'add', duration: 2 })
    }
  }
}
const handleCancelModal = (value: boolean) => {
  isOpenModal.value = value
  isEdit.value = false
}
</script>
