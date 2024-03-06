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
    <a-table :pagination="false" :columns="columns" :data-source="dataSource" bordered>
      <template #bodyCell="{ column, text, record }">
        <template v-if="['name', 'age', 'address'].includes(column.dataIndex)">
          <div>
            <a-input
              v-if="editableData[record.key]"
              v-model:value="editableData[record.key][column.dataIndex]"
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
              v-if="!editableData[record.key]"
              class="mr-2"
              :to="`/product/detail?slug=${record.key}`"
              >Detail</router-link
            >
            <div class="mr-2">
              <span v-if="editableData[record.key]">
                <a-typography-link class="mr-2" @click="save(record.key)">Save</a-typography-link>
                <a-popconfirm title="Sure to cancel?" @confirm="cancel(record.key)">
                  <a>Cancel</a>
                </a-popconfirm>
              </span>
              <span v-else>
                <a @click="edit(record.key)">Edit</a>
              </span>
            </div>
            <div v-if="!editableData[record.key]">
              <a-popconfirm
                v-if="dataSource.length"
                title="Sure to delete?"
                @confirm="onDelete(record.key)"
              >
                <a>Delete</a>
              </a-popconfirm>
            </div>
          </div>
        </template>
      </template>
    </a-table>
    <a-pagination v-model:current="current" class="mt-2" :total="500" @change="onChangePage" />
  </div>
</template>
<script lang="ts" setup>
import { reactive, ref } from 'vue'
import type { UnwrapRef } from 'vue'
// import ProductService from '../../services/ProductService'

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
interface DataItem {
  key: string
  name: string
  age: number
  address: string
}

const dataSource = ref(null)
const valueSearch = ref<string>('')
fetch('https://dummyjson.com/products')
  .then((res) => {
    console.log(res)

    dataSource.value = [
      {
        id: 1,
        title: 'iPhone 9',
        description: 'An apple mobile which is nothing like apple',
        price: 549,
        discountPercentage: 12.96,
        rating: 4.69,
        stock: 94,
        brand: 'Apple',
        category: 'smartphones',
        thumbnail: 'https://cdn.dummyjson.com/product-images/1/thumbnail.jpg',
        images: [
          'https://cdn.dummyjson.com/product-images/1/1.jpg',
          'https://cdn.dummyjson.com/product-images/1/2.jpg',
          'https://cdn.dummyjson.com/product-images/1/3.jpg',
          'https://cdn.dummyjson.com/product-images/1/4.jpg',
          'https://cdn.dummyjson.com/product-images/1/thumbnail.jpg',
        ],
      },
      {
        id: 2,
        title: 'iPhone X',
        description:
          'SIM-Free, Model A19211 6.5-inch Super Retina HD display with OLED technology A12 Bionic chip with ...',
        price: 899,
        discountPercentage: 17.94,
        rating: 4.44,
        stock: 34,
        brand: 'Apple',
        category: 'smartphones',
        thumbnail: 'https://cdn.dummyjson.com/product-images/2/thumbnail.jpg',
        images: [
          'https://cdn.dummyjson.com/product-images/2/1.jpg',
          'https://cdn.dummyjson.com/product-images/2/2.jpg',
          'https://cdn.dummyjson.com/product-images/2/3.jpg',
          'https://cdn.dummyjson.com/product-images/2/thumbnail.jpg',
        ],
      },
      {
        id: 3,
        title: 'Samsung Universe 9',
        description: "Samsung's new variant which goes beyond Galaxy to the Universe",
        price: 1249,
        discountPercentage: 15.46,
        rating: 4.09,
        stock: 36,
        brand: 'Samsung',
        category: 'smartphones',
        thumbnail: 'https://cdn.dummyjson.com/product-images/3/thumbnail.jpg',
        images: ['https://cdn.dummyjson.com/product-images/3/1.jpg'],
      },
      {
        id: 4,
        title: 'OPPOF19',
        description: 'OPPO F19 is officially announced on April 2021.',
        price: 280,
        discountPercentage: 17.91,
        rating: 4.3,
        stock: 123,
        brand: 'OPPO',
        category: 'smartphones',
        thumbnail: 'https://cdn.dummyjson.com/product-images/4/thumbnail.jpg',
        images: [
          'https://cdn.dummyjson.com/product-images/4/1.jpg',
          'https://cdn.dummyjson.com/product-images/4/2.jpg',
          'https://cdn.dummyjson.com/product-images/4/3.jpg',
          'https://cdn.dummyjson.com/product-images/4/4.jpg',
          'https://cdn.dummyjson.com/product-images/4/thumbnail.jpg',
        ],
      },
      {
        id: 5,
        title: 'Huawei P30',
        description:
          'Huawei’s re-badged P30 Pro New Edition was officially unveiled yesterday in Germany and now the device has made its way to the UK.',
        price: 499,
        discountPercentage: 10.58,
        rating: 4.09,
        stock: 32,
        brand: 'Huawei',
        category: 'smartphones',
        thumbnail: 'https://cdn.dummyjson.com/product-images/5/thumbnail.jpg',
        images: [
          'https://cdn.dummyjson.com/product-images/5/1.jpg',
          'https://cdn.dummyjson.com/product-images/5/2.jpg',
          'https://cdn.dummyjson.com/product-images/5/3.jpg',
        ],
      },
      {
        id: 6,
        title: 'MacBook Pro',
        description:
          'MacBook Pro 2021 with mini-LED display may launch between September, November',
        price: 1749,
        discountPercentage: 11.02,
        rating: 4.57,
        stock: 83,
        brand: 'Apple',
        category: 'laptops',
        thumbnail: 'https://cdn.dummyjson.com/product-images/6/thumbnail.png',
        images: [
          'https://cdn.dummyjson.com/product-images/6/1.png',
          'https://cdn.dummyjson.com/product-images/6/2.jpg',
          'https://cdn.dummyjson.com/product-images/6/3.png',
          'https://cdn.dummyjson.com/product-images/6/4.jpg',
        ],
      },
      {
        id: 7,
        title: 'Samsung Galaxy Book',
        description:
          'Samsung Galaxy Book S (2020) Laptop With Intel Lakefield Chip, 8GB of RAM Launched',
        price: 1499,
        discountPercentage: 4.15,
        rating: 4.25,
        stock: 50,
        brand: 'Samsung',
        category: 'laptops',
        thumbnail: 'https://cdn.dummyjson.com/product-images/7/thumbnail.jpg',
        images: [
          'https://cdn.dummyjson.com/product-images/7/1.jpg',
          'https://cdn.dummyjson.com/product-images/7/2.jpg',
          'https://cdn.dummyjson.com/product-images/7/3.jpg',
          'https://cdn.dummyjson.com/product-images/7/thumbnail.jpg',
        ],
      },
    ]
  })
  .then((json) => console.log(json))

const editableData: UnwrapRef<Record<string, DataItem>> = reactive({})

const edit = (key: string) => {
  editableData[key] = dataSource.value.filter((item) => key === item.key)[0]
}
const save = (key: string) => {
  Object.assign(dataSource.value.filter((item) => key === item.key)[0], editableData[key])
  delete editableData[key]
}
const cancel = (key: string) => {
  delete editableData[key]
}
const onDelete = (key: string) => {
  dataSource.value = dataSource.value.filter((item) => item.key !== key)
}
const onSearch = async () => {
  // const res = await ProductService.getProductAll()
  // console.log(res)
}
const onChangePage = (e: number) => {
  console.log(e)
}
</script>
