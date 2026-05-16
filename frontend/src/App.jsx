import { Routes, Route } from 'react-router-dom';
import Layout from './components/Layout';
import Home from './pages/Home';
import Products from './pages/Products';
import ProductDetail from './pages/ProductDetail';
import Cart from './pages/Cart';
import Checkout from './pages/Checkout';
import Login from './pages/Login';
import Register from './pages/Register';
import Wishlist from './pages/Wishlist';
import Orders from './pages/Orders';
import CmsPage from './pages/CmsPage';
import FAQ from './pages/FAQ';
import AdminLayout from './admin/AdminLayout';
import Dashboard from './admin/Dashboard';
import AdminProducts from './admin/AdminProducts';
import AdminOrders from './admin/AdminOrders';
import AdminCustomers from './admin/AdminCustomers';
import AdminGeneric from './admin/AdminGeneric';

export default function App() {
  return (
    <Routes>
      <Route element={<Layout />}>
        <Route index element={<Home />} />
        <Route path="products" element={<Products />} />
        <Route path="products/:slug" element={<ProductDetail />} />
        <Route path="cart" element={<Cart />} />
        <Route path="checkout" element={<Checkout />} />
        <Route path="wishlist" element={<Wishlist />} />
        <Route path="orders" element={<Orders />} />
        <Route path="login" element={<Login />} />
        <Route path="register" element={<Register />} />
        <Route path="faq" element={<FAQ />} />
        <Route path=":slug" element={<CmsPage />} />
      </Route>

      <Route path="/admin" element={<AdminLayout />}>
        <Route index element={<Dashboard />} />
        <Route path="products" element={<AdminProducts />} />
        <Route path="orders" element={<AdminOrders />} />
        <Route path="customers" element={<AdminCustomers />} />
        <Route path="categories" element={<AdminGeneric title="Categories" endpoint="/admin/categories" columns={[{ key: 'name', label: 'Name' }, { key: 'slug', label: 'Slug' }, { key: 'is_active', label: 'Active', render: (i) => i.is_active ? 'Yes' : 'No' }]} />} />
        <Route path="coupons" element={<AdminGeneric title="Coupons" endpoint="/admin/coupons" columns={[{ key: 'code', label: 'Code' }, { key: 'type', label: 'Type' }, { key: 'value', label: 'Value' }, { key: 'is_active', label: 'Active', render: (i) => i.is_active ? 'Yes' : 'No' }]} />} />
        <Route path="reviews" element={<AdminGeneric title="Reviews" endpoint="/admin/reviews" columns={[{ key: 'product', label: 'Product', render: (i) => i.product?.name }, { key: 'rating', label: 'Rating' }, { key: 'status', label: 'Status' }]} />} />
        <Route path="banners" element={<AdminGeneric title="Banners" endpoint="/admin/banners" columns={[{ key: 'title', label: 'Title' }, { key: 'is_active', label: 'Active', render: (i) => i.is_active ? 'Yes' : 'No' }]} />} />
        <Route path="settings" element={<AdminGeneric title="Settings" endpoint="/admin/settings" columns={[{ key: 'key', label: 'Key' }]} />} />
      </Route>
    </Routes>
  );
}
