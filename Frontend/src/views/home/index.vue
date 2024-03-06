<template>
  <div class="p-8">
    <div>
      <a-input-search
        v-model:value="valueSearch"
        class="mb-2 w-[200px]"
        placeholder="Search product"
        @search="onSearch"
      />
    </div>
    <a-table :pagination="false" :columns="columns" :data-source="productList?.products" bordered>
      <template #bodyCell="{ column, text, record }">
        <template v-if="['name', 'age', 'address'].includes(column.dataIndex)">
          <div>
            <a-input
              v-if="editableData[record.id]"
              v-model:value="editableData[record.id][column.dataIndex]"
              class="my-[-5px]"
            />
            <template v-else>
              {{ text }}
            </template>
          </div>
        </template>
        <!-- <template v-if="['image'].includes(column.dataIndex)">
          <img :src="editableData[record.key]" alt="" />
        </template> -->
        <template v-else-if="column.dataIndex === 'operation'">
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
              <!-- v-if="productList?.product?.length" -->
              <a-popconfirm title="Sure to delete?" @confirm="onDelete(record.id)">
                <a>Delete</a>
              </a-popconfirm>
            </div>
          </div>
        </template>
      </template>
    </a-table>
    <a-pagination
      v-model="pagination.skip"
      class="mt-2"
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
    width: '20px',
  },
  {
    title: 'Title',
    dataIndex: 'title',
    width: '15%',
  },
  {
    title: 'Description',
    dataIndex: 'description',
    width: '40%',
  },
  {
    title: 'Price',
    dataIndex: 'price',
    width: '40%',
  },
  // {
  //   title: 'Image',
  //   dataIndex: 'images',
  //   width: '40%',
  // },
  {
    title: 'operation',
    dataIndex: 'operation',
  },
]
interface ProductItem {
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
interface Pagination {
  skip: number
  limit: number
  total: number
}

const productList = ref<ProductItem[] | null>(null)
const pagination = ref<Pagination>({
  skip: 1,
  limit: 5,
  total: 10,
})
const valueSearch = ref<string>('')
const getProductAll = async () => {
  // const params = {
  //   limit: pagination.value.limit,
  //   skip: pagination.value.skip,
  // }
  // const res = await ProductService.getProductAll(params)
  // console.log(res)

  fetch(
    `https://dummyjson.com/products?limit=${pagination.value.limit}&skip=${pagination.value.skip}`,

    {
      method: 'GET',
      mode: 'cors',
      headers: {
        'Content-Type': 'application/json',
      },
    },
  )
    .then((res) => res.json())
    .then((data) => {
      productList.value = data
      const { skip, limit, total } = data
      pagination.value = {
        skip,
        limit,
        total,
      }
    })
}
getProductAll()

const editableData: UnwrapRef<Record<string, ProductItem>> = reactive({})

const edit = (id: number) => {
  console.log(id)
}
const save = (id: number) => {
  console.log(id)
}
const cancel = (id: number) => {
  delete editableData[id]
}
const onDelete = (id: number) => {
  fetch(
    `https://dummyjson.com/products/${id}`,

    {
      method: 'DELETE',
      mode: 'cors',
      headers: {
        'Content-Type': 'application/json',
      },
    },
  )
    .then((data) => {
      console.log(data)

      message.success({ content: 'Xoá sản phẩm thành công', key: 'product', duration: 2 })
    })
    .catch(() => {
      message.error({ content: 'Xoá sản phẩm không thành công', key: 'product', duration: 2 })
    })
}
const onSearch = async () => {
  const res = await ProductService.getProductAll()
  console.log(res)
}
const onChangePage = (e: number) => {
  pagination.value.skip = e
  getProductAll()
}
</script>
