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
  createProduct(payload) {
    return axiosInstance.post('/products/add', payload)
  }
  updateProduct({ id, payload }) {
    return axiosInstance.put(`/products/${id}`, payload)
  }
}

export default new ProductService()
