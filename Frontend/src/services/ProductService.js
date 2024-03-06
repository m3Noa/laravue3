import axiosInstance from '../plugins/axios'

class ProductService {
  getProductAll(params) {
    return axiosInstance.get('/products', params)
  }
}

export default new ProductService()
