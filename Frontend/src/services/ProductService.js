import axiosInstance from '../plugins/axios'

class ProductService {
  getProductAll(params) {
    return axiosInstance.get('/products', { params })
  }
  getProductDetail(id) {
    return axiosInstance.get('/products', id)
  }
  getProductSearch(id) {
    return axiosInstance.get('/products/search?q=' + id)
  }
  deleteProduct(id) {
    return axiosInstance.delete(`/products/${id}`)
  }
}

export default new ProductService()
