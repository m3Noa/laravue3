import axiosInstance from '../plugins/axios'

class ProductService {
  getProductAll() {
    return axiosInstance.get('/products')
  }
}

export default new ProductService()
