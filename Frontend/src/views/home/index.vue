<template>
  <div class="p-8">
    <a-table :pagination="false" :columns="columns" :data-source="productList?.products" bordered>
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
            <router-link
              v-if="!editableData[record.id]"
              class="mr-2"
              :to="`/product/detail?slug=${record.id}`"
              >Detail</router-link
            >
            <div class="mr-2">
              <span v-if="editableData[record.id]">
                <a-typography-link class="mr-2" @click="save(record.id)">Save</a-typography-link>
                <a-popconfirm title="Sure to cancel?" @confirm="cancel(record.id)">
                  <a>Cancel</a>
                </a-popconfirm>
              </span>
              <span v-else>
                <a @click="edit(record.id)">Edit</a>
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
  </div>
</template>
<script lang="ts" setup>
import { reactive, ref } from 'vue'
import type { UnwrapRef } from 'vue'
import { message } from 'ant-design-vue'
import ProductService from '../../services/ProductService'

const columns = [
  {
    title: 'ID',
    dataIndex: 'id',
    width: '40px',
  },
  {
    title: 'Title',
    dataIndex: 'title',
    width: '15%',
  },
  {
    title: 'Thumbnail',
    dataIndex: 'thumbnail',
    width: '80px',
  },
  {
    title: 'Description',
    dataIndex: 'description',
  },
  {
    title: 'Price',
    dataIndex: 'price',
    width: '100px',
    align: 'right',
  },
  {
    title: 'Discount',
    dataIndex: 'discountPercentage',
    width: '100px',
    align: 'right',
  },
  {
    title: 'Action',
    dataIndex: 'action',
    width: '150px',
  },
]
export interface ProductItem {
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
  images: string[]
}
export interface Pagination {
  skip: number
  limit: number
  total: number
}

const isLoading = ref<boolean>(false)
const productList = ref<ProductItem[] | null>(null)
const pagination = ref<Pagination>({
  skip: 1,
  limit: 5,
  total: 10,
})

const editableData: UnwrapRef<Record<string, ProductItem>> = reactive({})

const getProductAll = async (page: number) => {
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

const edit = (id: number) => {
  console.log(id)
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
</script>
